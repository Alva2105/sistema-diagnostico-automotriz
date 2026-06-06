<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SolicitudController extends Controller
{
    /* ══════════════════════════════════════════════════════════════
    |  PANEL DE ADMINISTRADOR
    ══════════════════════════════════════════════════════════════ */

    /**
     * Listado paginado — solo registros activos (sin deleted_at).
     * También pasa $eliminadas para la sección Papelera.
     */
    public function listar(Request $request)
    {
        $query = Solicitud::with(['cliente', 'vehiculo'])
            ->orderByDesc('fec_sol');

        if ($buscar = $request->get('buscar')) {
            $query->where(function ($q) use ($buscar) {
                $q->where('cod_solicitudes', 'ilike', "%{$buscar}%")
                  ->orWhere('tma_sol',  'ilike', "%{$buscar}%")
                  ->orWhere('est_sol',  'ilike', "%{$buscar}%")
                  ->orWhere('ser_sol',  'ilike', "%{$buscar}%")
                  ->orWhere('obs_sol',  'ilike', "%{$buscar}%")
                  ->orWhereHas('cliente', fn ($c) =>
                      $c->where('nom_cli', 'ilike', "%{$buscar}%")
                        ->orWhere('app_cli', 'ilike', "%{$buscar}%")
                  )
                  ->orWhereHas('vehiculo', fn ($v) =>
                      $v->where('mar_veh', 'ilike', "%{$buscar}%")
                        ->orWhere('mod_veh', 'ilike', "%{$buscar}%")
                  );
            });
        }

        $solicitudes = $query->paginate(10)->appends($request->query());

        // Registros en la papelera (onlyTrashed), ordenados por fecha de eliminación
        $eliminadas  = Solicitud::onlyTrashed()
            ->with(['cliente', 'vehiculo'])
            ->orderByDesc('deleted_at')
            ->get();

        $tecnicos = Usuario::where('cod_roles_usu', 3)->get();

        return view('admin.dashboardSolicitudes',
            compact('solicitudes', 'eliminadas', 'tecnicos'));
    }

    /**
     * Guardar nueva solicitud.
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'cod_clientes_sol'  => 'required|exists:clientes,cod_clientes',
            'cod_vehiculos_sol' => 'required|string',
            'tma_sol'           => 'required|string',
            'est_sol'           => 'required|in:Pendiente,En_Proceso,Finalizado,Cancelado',
            'fec_sol'           => 'required|date',
            'ser_sol'           => 'nullable|string|max:150',
            'obs_sol'           => 'nullable|string',
            'fpr_sol'           => 'nullable|date',
            'hpr_sol'           => 'nullable|string|max:10',
        ]);

        $ultimo = Solicitud::withTrashed()->orderByDesc('cod_solicitudes')->first();
        $num    = $ultimo
            ? (intval(preg_replace('/\D/', '', $ultimo->cod_solicitudes)) + 1)
            : 1;
        $cod = 'SOL-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        Solicitud::create([
            'cod_solicitudes'  => $cod,
            'cod_clientes_sol' => $request->cod_clientes_sol,
            'cod_vehiculos_sol'=> $request->cod_vehiculos_sol,
            'tma_sol'          => $request->tma_sol,
            'est_sol'          => $request->est_sol,
            'fec_sol'          => $request->fec_sol,
            'ser_sol'          => $request->ser_sol,
            'obs_sol'          => $request->obs_sol,
            'fpr_sol'          => $request->fpr_sol,
            'hpr_sol'          => $request->hpr_sol,
        ]);

        return redirect()->route('dashboard.solicitudes')
                         ->with('success', 'Solicitud creada correctamente.');
    }

    /**
     * Actualizar solicitud completa.
     */
    public function actualizar(Request $request, string $id)
    {
        $solicitud = Solicitud::findOrFail($id);

        $request->validate([
            'cod_clientes_sol'  => 'required|exists:clientes,cod_clientes',
            'cod_vehiculos_sol' => 'required|string',
            'tma_sol'           => 'required|string',
            'est_sol'           => 'required|in:Pendiente,En_Proceso,Finalizado,Cancelado',
            'fec_sol'           => 'required|date',
            'ser_sol'           => 'nullable|string|max:150',
            'obs_sol'           => 'nullable|string',
            'fpr_sol'           => 'nullable|date',
            'hpr_sol'           => 'nullable|string|max:10',
        ]);

        $solicitud->update([
            'cod_clientes_sol' => $request->cod_clientes_sol,
            'cod_vehiculos_sol'=> $request->cod_vehiculos_sol,
            'tma_sol'          => $request->tma_sol,
            'est_sol'          => $request->est_sol,
            'fec_sol'          => $request->fec_sol,
            'ser_sol'          => $request->ser_sol,
            'obs_sol'          => $request->obs_sol,
            'fpr_sol'          => $request->fpr_sol,
            'hpr_sol'          => $request->hpr_sol,
        ]);

        return redirect()->route('dashboard.solicitudes')
                         ->with('success', 'Solicitud actualizada correctamente.');
    }

    /**
     * Actualizar solo el estado (AJAX inline).
     */
    public function actualizarEstado(Request $request, $id)
    {
        try {
            $solicitud          = Solicitud::findOrFail($id);
            $solicitud->est_sol = $request->input('est_sol');
            $solicitud->save();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * BORRADO LÓGICO — mueve a la papelera (sets deleted_at).
     */
    public function eliminar(string $id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $solicitud->delete();   // SoftDeletes: NO borra físicamente, solo pone deleted_at

        return redirect()->route('dashboard.solicitudes')
                         ->with('success', 'Solicitud movida a la papelera.');
    }

    /**
     * RESTAURAR desde la papelera (limpia deleted_at).
     */
    public function restaurar(string $id)
    {
        $solicitud = Solicitud::onlyTrashed()->findOrFail($id);
        $solicitud->restore();
        $solicitud->restored_at = now();  // ← guarda la fecha de restauración
        $solicitud->save();

        return redirect()->route('dashboard.solicitudes')
                         ->with('success', 'Solicitud restaurada correctamente.');
    }

    /**
     * Vehículos de un cliente en JSON (para el select dinámico del modal).
     */
    public function vehiculosPorCliente(string $codCliente)
    {
        $vehiculos = Vehiculo::where('cod_clientes_veh', $codCliente)
            ->select('cod_vehiculos', 'mar_veh', 'mod_veh', 'ani_veh')
            ->orderBy('mar_veh')
            ->get();

        return response()->json($vehiculos);
    }

    /* ══════════════════════════════════════════════════════════════
    |  PANEL DE CLIENTE
    ══════════════════════════════════════════════════════════════ */

    public function crear(Request $request)
    {
        $usuario  = Auth::user();
        $cliente  = Cliente::where('email_cli', $usuario->email_usu)->first();

        if (!$cliente) {
            return redirect()->route('welcome')
                ->with('error', 'No se encontró tu perfil de cliente.');
        }

        $vehiculos = Vehiculo::where('cod_clientes_veh', $cliente->cod_clientes)->get();

        if ($vehiculos->isEmpty()) {
            return redirect()->route('vehiculos.registrar')
                ->with('error', 'Registra al menos un vehículo antes de crear una solicitud.');
        }

        return view('client.solicitud', [
            'usuario'   => $usuario,
            'cliente'   => $cliente,
            'vehiculos' => $vehiculos,
        ]);
    }

    public function enviar(Request $request)
    {
        $usuario = Auth::user();
        $cliente = Cliente::where('email_cli', $usuario->email_usu)->firstOrFail();

        $request->validate([
            'vehiculo_id' => [
                'required', 'string',
                Rule::exists('vehiculos', 'cod_vehiculos')->where(fn ($q) =>
                    $q->where('cod_clientes_veh', $cliente->cod_clientes)
                ),
            ],
            'tipo_mantenimiento'  => 'required|string',
            'ser_sol'             => 'nullable|string|max:150',
            'descripcion'         => 'nullable|string|max:1000',
            'fecha_preferida'     => 'nullable|date',
            'hora_preferida_real' => 'nullable|string|max:10',
        ]);

        // Reutilizar withTrashed para no repetir un código eliminado
        $ultimo = Solicitud::withTrashed()->orderByDesc('cod_solicitudes')->first();
        $num    = $ultimo
            ? (intval(preg_replace('/\D/', '', $ultimo->cod_solicitudes)) + 1)
            : 1;
        $cod = 'SOL-' . str_pad($num, 4, '0', STR_PAD_LEFT);

        Solicitud::create([
            'cod_solicitudes'  => $cod,
            'cod_clientes_sol' => $cliente->cod_clientes,
            'cod_vehiculos_sol'=> $request->vehiculo_id,
            'tma_sol'          => str_contains(strtoupper($request->tipo_mantenimiento), 'PREVENTIVO')
                                    ? 'Mantenimiento Preventivo'
                                    : 'Mantenimiento Correctivo',
            'ser_sol'          => $request->ser_sol,
            'obs_sol'          => $request->descripcion,
            'fpr_sol'          => $request->fecha_preferida     ?: null,
            'hpr_sol'          => $request->hora_preferida_real ?: null,
            'est_sol'          => 'Pendiente',
        ]);

        return redirect()->route('cliente.solicitudes')
            ->with('success', '¡Tu solicitud fue enviada correctamente!');
    }

    public function listarCliente()
    {
        $usuario     = Auth::user();
        $cliente     = Cliente::where('email_cli', $usuario->email_usu)->firstOrFail();
        $solicitudes = $cliente->solicitudes()
            ->with('vehiculo')
            ->orderBy('cod_solicitudes', 'desc')
            ->paginate(10);

        return view('client.Cliente-solicitudes', compact('solicitudes', 'cliente'));
    }

    public function ver($id)
    {
        $solicitud = Solicitud::with([
            'cliente', 'vehiculo',
            'asignaciones.usuario',
            'diagnostico.cotizacion'
        ])->findOrFail($id);

        return view('admin.solicitudDetalle', compact('solicitud'));
    }

    public function reporte(Request $request)
    {
        $query = Solicitud::with(['cliente', 'vehiculo'])
            ->orderByDesc('fec_sol');

        if ($request->filled('estado'))
            $query->where('est_sol', $request->estado);

        if ($request->filled('tipo'))
            $query->where('tma_sol', 'ilike', '%' . $request->tipo . '%');

        if ($request->filled('desde'))
            $query->whereDate('fec_sol', '>=', $request->desde);

        if ($request->filled('hasta'))
            $query->whereDate('fec_sol', '<=', $request->hasta);

        if ($request->filled('cliente'))
            $query->whereHas('cliente', fn($q) =>
                $q->where('nom_cli', 'ilike', '%' . $request->cliente . '%')
                  ->orWhere('app_cli', 'ilike', '%' . $request->cliente . '%')
            );

        $solicitudes = $query->get();

        return view('admin.reporteSolicitudes', compact('solicitudes'));
    }
}