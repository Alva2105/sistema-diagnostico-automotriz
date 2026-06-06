<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificarEstadoUsuario
{
    public function handle(Request $request, Closure $next)
    {
        $usuario = Auth::user()->usuario ?? null;

        if ($usuario && $usuario->est_usu === 'Baneado') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'baneado' => 'Tu cuenta ha sido baneada. Contacta con soporte.'
            ]);
        }

        return $next($request);
    }
}