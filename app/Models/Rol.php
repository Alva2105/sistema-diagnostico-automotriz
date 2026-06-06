<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * CAMBIOS:
 * - Tabla pasa de "roles" a "roles" (igual, sin cambio en nombre)
 * - PK pasa de "cod_rol" → "cod_roles"
 * - Se elimina el campo "des_rol" (descripción), la nueva BD solo tiene "nom_rol"
 */
class Rol extends Model
{
    protected $table = 'roles';

    // Nueva PK
    protected $primaryKey = 'cod_roles';

    public $timestamps = false;

    protected $fillable = [
        'nom_rol',
        // "des_rol" fue eliminado en la nueva BD
    ];

    /**
     * Un rol tiene muchos usuarios.
     * FK en usuarios: "cod_roles_usu"  →  PK aquí: "cod_roles"
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'cod_roles_usu', 'cod_roles');
    }
}