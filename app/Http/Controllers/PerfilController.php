<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * PERFIL CONTROLLER
 * 
 * CAMBIOS:
 * - perfilCliente(): antes cargaba 'registro' y 'cliente' via relaciones.
 *   Ahora el usuario tiene sus datos directos. El cliente se busca por email.
 * - perfilAdmin(): antes cargaba 'registro' y 'superadmin'. Ahora solo el usuario.
 * - perfilConductor(): ELIMINADO - el rol Conductor ya no existe.
 * - La verificación de rol usa "cod_roles_usu" en vez de "cod_rol".
 * - La imagen se guarda igual (img_usu en tabla usuarios).
 */
class PerfilController extends Controller
{
    public function perfilCliente()
    {
        $usuario = Auth::user();

        // CAMBIO: antes era cod_rol != 3, ahora es cod_roles_usu != 3
        if ($usuario->cod_roles_usu != 4) {
            abort(403, 'ROL NO AUTORIZADO');
        }

        // CAMBIO: antes cargaba 'registro' y 'cliente' como relaciones.
        // Ahora los datos del usuario están en el mismo modelo.
        // El cliente se busca por email para mostrar datos extras (tel, dir).
        $cliente = \App\Models\Cliente::where('email_cli', $usuario->email_usu)->first();

        return view('client.perfilCliente', compact('usuario', 'cliente'));
    }

    public function perfilAdmin()
    {
        $usuario = Auth::user();

        // CAMBIO: cod_roles_usu (antes cod_rol)
        if ($usuario->cod_roles_usu != 1) {
            abort(403, 'ROL NO AUTORIZADO');
        }

        // CAMBIO: ya no se cargan 'registro' ni 'superadmin' (no existen como relaciones)
        // Los datos del admin están directamente en $usuario
        return view('admin.perfilAdmin', compact('usuario'));
    }

    public function perfilGerente()
    {
        return view('gerente.perfilGerente', ['usuario' => Auth::user()]);
    }

    public function perfilTecnico()
    {
        return view('technical.perfilTecnico', ['usuario' => Auth::user()]);
    }

    /**
     * ELIMINADO: perfilConductor()
     * El rol Conductor ya no existe en la nueva BD.
     * La ruta /conductor/perfil debe ser eliminada también de web.php.
     */

    public function actualizarImagen(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048',
        ]);

        $usuario = Auth::user();

        // Eliminar imagen anterior
        if ($usuario->img_usu && file_exists(storage_path('app/public/' . $usuario->img_usu))) {
            unlink(storage_path('app/public/' . $usuario->img_usu));
        }

        $path = $request->file('avatar')->store('usuarios', 'public');

        // CAMBIO: img_usu sigue siendo el campo, sin cambios aquí
        $usuario->img_usu = $path;
        $usuario->save();

        return back()->with('success', 'Imagen actualizada correctamente.');
    }
}