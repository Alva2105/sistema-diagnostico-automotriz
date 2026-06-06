<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Registro;

class RegistroUserProvider implements UserProvider
{
    public function retrieveById($identifier)
    {
        return Usuario::with('registro')->find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        $user = Usuario::find($identifier);
        return $user && $user->getRememberToken() === $token ? $user : null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        $user->setRememberToken($token);
        $user->save();
    }

    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'] ?? null;

        if (! $email) {
            return null;
        }

        $registro = Registro::where('coe_reg', $email)->first();

        return $registro ? Usuario::where('cod_reg', $registro->cod_reg)->first() : null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $registro = $user->registro;
        $password = $credentials['password'] ?? null;

        return $registro && $password && Hash::check($password, $registro->con_reg);
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        $registro = $user->registro;
        $password = $credentials['password'] ?? null;

        if (! $registro || ! $password) {
            return;
        }

        if ($force || Hash::needsRehash($registro->con_reg)) {
            $registro->con_reg = Hash::make($password);
            $registro->save();
        }
    }
}
