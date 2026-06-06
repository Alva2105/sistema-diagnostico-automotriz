<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Rol;
use App\Exports\UsuariosExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * CAMBIOS RESPECTO AL ORIGINAL:
 * - Se ELIMINAN los imports de Registro (ya no existe)
 * - registrar(): Cliente::create() ya no usa 'ci_cli' (no existe en BD).
 *   El CI del cliente VA en 'cod_clientes' (la PK varchar 20).
 * - El registro crea DOS registros: uno en "usuarios" y uno en "clientes"
 * - Los listados consultan directamente "usuarios" filtrando por "cod_roles_usu"
 * - Se ELIMINA listarConductores() (el rol Conductor ya no existe)
 */
class RegistroController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'appaterno'  => 'required|string|max:100',
            'apmaterno'  => 'nullable|string|max:100',
            'correo'     => 'required|email|unique:usuarios,email_usu',
            'contrasena' => 'required|min:6',
            'CI'         => 'required|string|max:20|unique:clientes,cod_clientes',
            'telefono'   => 'nullable|string|max:20',
            'direccion'  => 'nullable|string|max:150',
        ]);

        DB::beginTransaction();

        try {
            // Crear usuario
            $usuario = Usuario::create([
                'nom_usu'       => $request->name,
                'app_usu'       => $request->appaterno,
                'apm_usu'       => $request->apmaterno,
                'email_usu'     => $request->correo,
                'pas_usu'       => Hash::make($request->contrasena),
                'cod_roles_usu' => 4, // Rol Cliente
            ]);

            // Crear cliente: el CI va en cod_clientes (la PK), NO en ci_cli
            Cliente::create([
                'cod_clientes' => $request->CI, // El CI ES la PK del cliente
                'nom_cli'      => $request->name,
                'app_cli'      => $request->appaterno,
                'apm_cli'      => $request->apmaterno,
                'tel_cli'      => $request->telefono,
                'dir_cli'      => $request->direccion,
                'email_cli'    => $request->correo,
            ]);

            DB::commit();

            return redirect()->route('login')
                ->with('success', 'Registro exitoso. Ya puedes iniciar sesión.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    public function listar()
    {
        $usuarios = Usuario::with('rol')
            ->orderBy('cod_usuarios', 'asc')
            ->paginate(10);
        $tipo = 'Todos';
        return view('admin.dashboardUsuarios', compact('usuarios', 'tipo'));
    }

    public function listarClientes()
    {
        $usuarios = Usuario::with('rol')
            ->where('cod_roles_usu', 4)
            ->orderBy('cod_usuarios', 'asc')
            ->paginate(10);
        $tipo = 'Clientes';
        return view('admin.dashboardUsuarios', compact('usuarios', 'tipo'));
    }

    public function listarTecnicos()
    {
        $usuarios = Usuario::with('rol')
            ->where('cod_roles_usu', 3)
            ->orderBy('cod_usuarios', 'asc')
            ->paginate(10);
        $tipo = 'Técnicos Automotrices';
        return view('admin.dashboardUsuarios', compact('usuarios', 'tipo'));
    }

    // ELIMINADO: listarConductores() → el rol Conductor fue eliminado de la nueva BD

    public function buscar(Request $request)
    {
        $texto = $request->input('q', '');

        $usuarios = Usuario::with('rol')
            ->where(function ($query) use ($texto) {
                $query->where('nom_usu',    'ILIKE', "%$texto%")
                      ->orWhere('app_usu',  'ILIKE', "%$texto%")
                      ->orWhere('apm_usu',  'ILIKE', "%$texto%")
                      ->orWhere('email_usu','ILIKE', "%$texto%");
            })
            ->orderBy('cod_usuarios', 'asc')
            ->get();

        return response()->json($usuarios);
    }

    /**
     * CAMBIO: la nueva BD no tiene campo "est_usu" en usuarios.
     * Si necesitas habilitar/deshabilitar usuarios, agrega ese campo a la migración.
     */
    public function actualizarEstado(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'El campo estado no existe en la nueva estructura de BD.'
        ], 422);
    }

    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nom_usu'       => 'required|string|max:100',
            'app_usu'       => 'required|string|max:100',
            'apm_usu'       => 'nullable|string|max:100',
            'email_usu'     => 'required|email|unique:usuarios,email_usu',
            'pas_usu'       => 'required|min:6|confirmed',
            'cod_roles_usu' => 'required|exists:roles,cod_roles',
            'img_usu'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $rutaImagen = null;

            if ($request->hasFile('img_usu')) {
                $rutaImagen = $request->file('img_usu')->store('usuarios', 'public');
            }

            Usuario::create([
                'nom_usu'       => $request->nom_usu,
                'app_usu'       => $request->app_usu,
                'apm_usu'       => $request->apm_usu,
                'email_usu'     => $request->email_usu,
                'pas_usu'       => Hash::make($request->pas_usu),
                'img_usu'       => $rutaImagen,
                'cod_roles_usu' => $request->cod_roles_usu,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Usuario registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar usuario: ' . $e->getMessage());
        }
    }

    public function actualizarUsuario(Request $request, $id)
    {
        $request->validate([
            'nom_usu'       => 'required|string|max:100',
            'app_usu'       => 'required|string|max:100',
            'apm_usu'       => 'nullable|string|max:100',
            'email_usu'     => 'required|email|unique:usuarios,email_usu,' . $id . ',cod_usuarios',
            'cod_roles_usu' => 'required|exists:roles,cod_roles',
            'img_usu'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $usuario = Usuario::findOrFail($id);

            if ($request->hasFile('img_usu')) {
                $rutaImagen      = $request->file('img_usu')->store('usuarios', 'public');
                $usuario->img_usu = $rutaImagen;
            }

            $usuario->nom_usu       = $request->nom_usu;
            $usuario->app_usu       = $request->app_usu;
            $usuario->apm_usu       = $request->apm_usu;
            $usuario->email_usu     = $request->email_usu;
            $usuario->cod_roles_usu = $request->cod_roles_usu;

            if ($request->filled('pas_usu')) {
                $request->validate(['pas_usu' => 'min:6|confirmed']);
                $usuario->pas_usu = Hash::make($request->pas_usu);
            }

            $usuario->save();

            return redirect()->back()->with('success', 'Usuario actualizado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar usuario.');
        }
    }

    public function eliminarUsuario($id)
    {
        try {
            $usuario = Usuario::findOrFail($id);

            if (($usuario->rol->nom_rol ?? '') === 'SuperAdmin') {
                return redirect()->back()->with('error', 'No puedes eliminar un SuperAdmin.');
            }

            $usuario->delete();

            return redirect()->back()->with('success', 'Usuario eliminado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar usuario.');
        }
    }

    public function exportarExcel()
    {
        return Excel::download(
            new UsuariosExport,
            'usuarios_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    public function exportarPDF()
    {
        $usuarios = Usuario::with('rol')
            ->orderBy('cod_usuarios', 'asc')
            ->get();

        $fecha = now()->format('d/m/Y H:i');

        $pdf = Pdf::loadView(
            'pdf.usuarios',
            compact('usuarios', 'fecha')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            'usuarios_' . now()->format('Ymd_His') . '.pdf'
        );
    }
}