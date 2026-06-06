<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * CAMBIOS:
 * - App\Models\User → App\Models\Usuario (User no existe en esta BD)
 * - Campos adaptados a la tabla "usuarios": nom_usu, app_usu, apm_usu, email_usu, pas_usu
 * - Se eliminan campos que no existen: apellido_paterno, ci, fecha_nacimiento, telefono, direccion
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom_usu'       => ['required', 'string', 'max:100'],
            'app_usu'       => ['required', 'string', 'max:100'],
            'apm_usu'       => ['nullable', 'string', 'max:100'],
            'email_usu'     => ['required', 'string', 'email', 'max:255', 'unique:usuarios,email_usu'],
            'pas_usu'       => ['required', 'string', 'min:6', 'confirmed'],
            'cod_roles_usu' => ['required', 'exists:roles,cod_roles'],
        ]);
    }

    protected function create(array $data)
    {
        return Usuario::create([
            'nom_usu'       => $data['nom_usu'],
            'app_usu'       => $data['app_usu'],
            'apm_usu'       => $data['apm_usu'] ?? null,
            'email_usu'     => $data['email_usu'],
            'pas_usu'       => Hash::make($data['pas_usu']),
            'cod_roles_usu' => $data['cod_roles_usu'],
        ]);
    }
}