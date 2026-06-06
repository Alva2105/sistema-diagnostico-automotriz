<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * CAMBIOS:
 * - Se ELIMINA 'Conductor' del mapa de roles (ya no existe en la nueva BD)
 * - Los códigos de rol ahora corresponden a cod_roles en tabla "roles"
 * - El campo del usuario cambió de "cod_rol" → "cod_roles_usu"
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $usuario = auth()->user();

        // Los IDs deben coincidir con los que insertes en la tabla "roles" de tu nueva BD
        $rolesMap = [
            'SuperAdmin'        => 1,
            'Gerente'           => 2,
            'TecnicoAutomotriz' => 3,
            'Cliente'           => 4,
        ];

        if (!isset($rolesMap[$role])) {
            return abort(403, 'Rol desconocido.');
        }

        // CAMBIO: antes era $usuario->cod_rol, ahora es $usuario->cod_roles_usu
        if ($usuario->cod_roles_usu != $rolesMap[$role]) {
            return abort(403, 'Acceso no autorizado.');
        }

        return $next($request);
    }
}