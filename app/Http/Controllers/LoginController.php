<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

/**
 * CAMBIOS:
 * - Se ELIMINA import de App\Models\Registro (ya no existe)
 * - Se ELIMINA case 5 (Conductor) ya que ese rol no existe en la nueva BD
 */
class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('welcome');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $usuario = Usuario::where('email_usu', $request->email)->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo no está registrado.']);
        }

        if (!Hash::check($request->password, $usuario->pas_usu)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        Auth::login($usuario);

        // Si vino con redirección (registro de vehículo)
        if ($request->filled('redirect')) {
            return redirect()->to($request->input('redirect'));
        }

        if ($request->has('redirect') && $request->redirect === 'registro-auto') {
            return redirect()->route('vehiculos.registrar');
        }

        // Redirección según rol
        switch ($usuario->cod_roles_usu) {
            case 1: // SuperAdmin
                return redirect()->route('dashboard');

            case 2: // Gerente
                return redirect()->route('gerente.inventarios');

            case 3: // Técnico Automotriz
                return redirect()->route('tecnico.asignaciones');

            case 4: // Cliente
                return redirect()->route('welcome');

            // ELIMINADO: case 5 (Conductor) ya no existe en la nueva BD

            default:
                return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}