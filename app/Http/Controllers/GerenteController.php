<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Solicitud;
use App\Models\Asignacion;
use App\Models\Diagnostico;
use App\Models\Cotizacion;
use App\Models\Mantenimiento;
use App\Models\Repuesto;
use App\Models\CategoriaRepuesto;
use App\Models\Vehiculo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * CAMBIOS RESPECTO AL ORIGINAL:
 * - Se ELIMINAN imports: TecnicoAutomotriz, MantPreventivo, MantCorrectivo,
 *   Inventario, Seguimiento (ya no existen como modelos separados)
 * - Se AÑADEN: Asignacion, Diagnostico, Cotizacion, CategoriaRepuesto
 * - inventarios() ahora muestra categorías de repuestos (no hay tabla inventarios)
 * - verInventario() filtra por categoría en vez de cod_inv
 * - actualizarStockInline() usa "stock" en vez de "cant_rep"
 * - El estado del stock ahora es un accessor calculado (estado_stock)
 * - solicitudes() ya no carga 'cliente.usuario.registro' → carga 'cliente' directo
 * - aprobarSolicitud() crea un flujo: Diagnostico → Cotizacion → Mantenimiento
 *   y usa tabla "asignaciones" para vincular técnico (ya no es campo cod_tau)
 * - kardexClientes() busca por cod_clientes (el CI) en lugar de ci_cli (no existe)
 * - listadoTecnicos() busca en campos de la tabla usuarios (nom_usu, etc.)
 * - Se ELIMINA verDetalleReporte() que dependía de Seguimiento
 */
class GerenteController extends Controller
{
    public function home()
    {
        return view('gerente.home', ['user' => Auth::user()]);
    }

    // =========================================================
    // INVENTARIO (ahora son Categorías de Repuestos)
    // =========================================================

    public function inventarios()
    {
        $user = Auth::user();
        $categorias = CategoriaRepuesto::withCount('repuestos')->get();
        return view('gerente.inventarios', compact('user', 'categorias'));
    }

    public function verInventario($cod_categoria)
    {
        $user = Auth::user();
        $categoria = CategoriaRepuesto::findOrFail($cod_categoria);

        $repuestos = Repuesto::where('cod_categoria_rep', $cod_categoria)
            ->orderBy('cod_repuestos', 'ASC')
            ->paginate(8);

        return view('gerente.inventarioTabla', compact('categoria', 'repuestos', 'user'));
    }

    public function actualizarStockInline(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:0'
        ]);

        $rep = Repuesto::findOrFail($id);
        $rep->stock = (int) $request->input('cantidad');
        $rep->save();

        return response()->json([
            'ok'     => true,
            'stock'  => $rep->stock,
            'estado' => $rep->estado_stock,
        ]);
    }

    public function actualizarImagenRepuestoAjax(Request $request, $id)
    {
        // La nueva BD no tiene img_rep en repuestos.
        return response()->json([
            'ok'  => false,
            'msg' => 'La nueva BD no tiene campo img_rep en repuestos. Agrega la columna si lo necesitas.'
        ], 422);
    }

    // =========================================================
    // SOLICITUDES Y APROBACIÓN
    // =========================================================

    public function solicitudes()
    {
        $user = Auth::user();

        $solicitudes = Solicitud::with(['cliente', 'vehiculo'])
            ->where('est_sol', 'Pendiente')
            ->orderBy('cod_solicitudes', 'desc')
            ->paginate(10);

        $tecnicos = Usuario::where('cod_roles_usu', 3)->get();

        return view('gerente.listadoSolicitudes', compact('user', 'solicitudes', 'tecnicos'));
    }

    public function aprobarSolicitud(Request $request, $cod_sol)
    {
        $request->validate([
            'cod_usuario_tecnico' => 'required|exists:usuarios,cod_usuarios',
        ]);

        DB::beginTransaction();

        try {
            $sol = Solicitud::findOrFail($cod_sol);

            if ($sol->est_sol !== 'Pendiente') {
                return response()->json(['ok' => false, 'msg' => 'La solicitud no está en estado PENDIENTE.'], 400);
            }

            $codTecnico = $request->input('cod_usuario_tecnico');

            // PASO 1: Asignar técnico
            Asignacion::create([
                'cod_solicitudes_asi' => $sol->cod_solicitudes,
                'cod_usuarios_asi'    => $codTecnico,
                'obs_asi'             => 'Asignado por Gerente al aprobar solicitud.',
            ]);

            // PASO 2: Crear diagnóstico inicial
            $diagnostico = Diagnostico::create([
                'des_dia'             => $sol->obs_sol ?? 'Diagnóstico pendiente de completar.',
                'cod_solicitudes_dia' => $sol->cod_solicitudes,
            ]);

            // PASO 3: Crear cotización inicial
            $cotizacion = Cotizacion::create([
                'mon_cot'              => 0.00,
                'est_cot'              => 'Pendiente',
                'cod_diagnosticos_cot' => $diagnostico->cod_diagnosticos,
            ]);

            // PASO 4: Crear mantenimiento
            $mantenimiento = Mantenimiento::create([
                'des_man'              => $sol->obs_sol ?? 'Mantenimiento en proceso.',
                'est_man'              => 'En Proceso',
                'est_ver_man'          => 'Pendiente',
                'cod_cotizaciones_man' => $cotizacion->cod_cotizaciones,
            ]);

            // PASO 5: Actualizar estado solicitud
            $sol->est_sol = 'En Proceso';
            $sol->save();

            DB::commit();

            return response()->json([
                'ok'      => true,
                'msg'     => 'Solicitud aprobada y mantenimiento creado.',
                'cod_man' => $mantenimiento->cod_mantenimientos,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('aprobarSolicitud error: ' . $e->getMessage());
            return response()->json([
                'ok'  => false,
                'msg' => 'Error al aprobar: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================
    // KARDEX CLIENTES
    // =========================================================

    /**
     * CAMBIO: antes buscaba por ci_cli (no existe en BD).
     * El CI del cliente ES el cod_clientes (la PK).
     * Se busca en: nom_cli, app_cli, apm_cli, cod_clientes (el CI), tel_cli.
     */
    public function kardexClientes(Request $request)
    {
        $user = Auth::user();

        $query = Cliente::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom_cli',      'ilike', "%{$search}%")
                  ->orWhere('app_cli',    'ilike', "%{$search}%")
                  ->orWhere('apm_cli',    'ilike', "%{$search}%")
                  ->orWhere('cod_clientes','ilike', "%{$search}%") // El CI es la PK
                  ->orWhere('tel_cli',    'ilike', "%{$search}%");
            });
        }

        $clientes = $query->orderBy('cod_clientes', 'asc')
            ->paginate(8)
            ->withQueryString();

        return view('gerente.kardexClientes', compact('user', 'clientes'));
    }

    // =========================================================
    // TÉCNICOS
    // =========================================================

    public function listadoTecnicos(Request $request)
    {
        $user = Auth::user();

        $tecnicos = Usuario::where('cod_roles_usu', 3)
            ->when($request->q, function ($q) use ($request) {
                $search = $request->q;
                $q->where('nom_usu',     'ilike', "%{$search}%")
                  ->orWhere('app_usu',   'ilike', "%{$search}%")
                  ->orWhere('apm_usu',   'ilike', "%{$search}%")
                  ->orWhere('email_usu', 'ilike', "%{$search}%");
            })
            ->paginate(15);

        return view('gerente.listadoTecnicos', compact('user', 'tecnicos'));
    }

    public function buscarTecnicos(Request $request)
    {
        $search = trim($request->q ?? '');

        try {
            $tecnicos = Usuario::where('cod_roles_usu', 3)
                ->when($search !== '', function ($q) use ($search) {
                    $s = mb_strtolower($search, 'UTF-8');
                    $q->where(function ($q2) use ($s) {
                        $q2->whereRaw('LOWER(nom_usu) LIKE ?',     ["%{$s}%"])
                           ->orWhereRaw('LOWER(app_usu) LIKE ?',   ["%{$s}%"])
                           ->orWhereRaw('LOWER(apm_usu) LIKE ?',   ["%{$s}%"])
                           ->orWhereRaw('LOWER(email_usu) LIKE ?', ["%{$s}%"]);
                    });
                })
                ->get();

            $result = $tecnicos->map(fn($t) => [
                'cod_usuarios' => $t->cod_usuarios,
                'nom_usu'      => $t->nom_usu,
                'app_usu'      => $t->app_usu,
                'apm_usu'      => $t->apm_usu,
                'email_usu'    => $t->email_usu,
                'img_usu'      => $t->img_usu,
            ]);

            return response()->json($result, 200);

        } catch (\Exception $e) {
            \Log::error('Error buscarTecnicos: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno al buscar técnicos'], 500);
        }
    }

    public function verReportesTecnico($cod_usuario)
    {
        $tecnico = Usuario::where('cod_usuarios', $cod_usuario)
            ->where('cod_roles_usu', 3)
            ->firstOrFail();

        $mantenimientos = Mantenimiento::whereHas(
            'cotizacion.diagnostico.solicitud.asignaciones',
            fn($q) => $q->where('cod_usuarios_asi', $cod_usuario)
        )
        ->with('cotizacion.diagnostico.solicitud.vehiculo')
        ->orderBy('fec_ini_man', 'desc')
        ->get();

        $groupedMantenimientos = $mantenimientos->groupBy(function ($m) {
            $d = Carbon::parse($m->fec_ini_man)->locale('es')->translatedFormat('l - d/m/Y');
            return mb_strtoupper(mb_substr($d, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($d, 1, null, 'UTF-8');
        });

        return view('gerente.reportesTecnicos', compact('tecnico', 'mantenimientos', 'groupedMantenimientos'));
    }

    public function verDetalleReporte($cod_usuario, $cod_man)
    {
        $tecnico       = Usuario::findOrFail($cod_usuario);
        $mantenimiento = Mantenimiento::with(['repuestos', 'cotizacion.diagnostico.solicitud'])
            ->findOrFail($cod_man);

        return view('gerente.detallesReportes', compact('tecnico', 'mantenimiento'));
    }

    public function notifiGerente()
    {
        $user = Auth::user();
        $notificaciones = \App\Models\Notificacion::where('cod_usuarios_not', $user->cod_usuarios)
            ->orderBy('fec_not', 'desc')
            ->paginate(20);

        return view('gerente.notifiGerente', compact('user', 'notificaciones'));
    }
}