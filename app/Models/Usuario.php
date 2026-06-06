<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * CAMBIOS RESPECTO A LA BD ANTERIOR:
 * - La tabla se llama ahora "usuarios" (plural) en lugar de "usuario"
 * - Ya NO existe la tabla "registro" separada. Todos los datos personales
 *   (nombre, apellidos, email, contraseña) están aquí directamente.
 * - La PK pasa de "cod_usu" a "cod_usuarios"
 * - El campo de email pasa de "coe_reg" (en registro) a "email_usu"
 * - El campo de password pasa de "con_reg" (en registro) a "pas_usu"
 * - Ya NO existe relación con Registro, Conductor, ni SuperAdmin como modelo separado
 * - "cod_rol" pasa a "cod_roles_usu" → FK a tabla "roles"
 * - Se eliminan: cla_usu (clave autogenerada), pre_usu, est_usu → 
 *   estos datos ya no existen en la nueva BD
 */
class Usuario extends Authenticatable
{
    use Notifiable;

    // Nueva tabla en plural
    protected $table = 'usuarios';

    // Nueva PK
    protected $primaryKey = 'cod_usuarios';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'nom_usu',       // Nombre (antes nom_reg en tabla registro)
        'app_usu',       // Apellido paterno (antes apa_reg)
        'apm_usu',       // Apellido materno (antes ama_reg), ahora nullable
        'email_usu',     // Email/correo (antes coe_reg en tabla registro)
        'pas_usu',       // Password hasheado (antes con_reg en tabla registro)
        'img_usu',       // Imagen de perfil
        'cod_roles_usu', // FK a roles (antes cod_rol)
    ];

    protected $hidden = [
        'pas_usu',
    ];

    // =========================================================
    // MÉTODOS REQUERIDOS POR LARAVEL AUTH
    // =========================================================

    /**
     * Laravel usa getAuthIdentifierName() para saber cuál es la PK.
     * Antes era 'cod_usu', ahora es 'cod_usuarios'.
     */
    public function getAuthIdentifierName(): string
    {
        return 'cod_usuarios';
    }

    /**
     * Laravel usa getAuthPassword() para comparar contraseñas.
     * Antes leía de registro->con_reg, ahora lee directo de pas_usu.
     */
    public function getAuthPassword(): string
    {
        return $this->pas_usu;
    }

    /**
     * El campo de email para Laravel (usado en "remember me", notificaciones).
     * Antes era un accessor que leía de registro->coe_reg.
     * Ahora apunta directo al campo email_usu.
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->email_usu;
    }

    // =========================================================
    // ACCESSORS
    // =========================================================

    /**
     * Nombre completo calculado a partir de los campos del mismo modelo.
     * Antes leía de $this->registro->nom_reg, etc.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nom_usu} {$this->app_usu} " . ($this->apm_usu ?? ''));
    }

    /**
     * Normaliza la ruta de la imagen para que siempre tenga el prefijo correcto.
     */
    public function getImgUsuAttribute($value): ?string
    {
        if (!$value) return null;
        if (!str_starts_with($value, 'usuarios/')) {
            return 'usuarios/' . $value;
        }
        return $value;
    }

    // =========================================================
    // RELACIONES
    // =========================================================

    /**
     * Un usuario tiene un rol.
     * FK cambió de "cod_rol" → "cod_roles_usu"
     * PK de roles cambió de "cod_rol" → "cod_roles"
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'cod_roles_usu', 'cod_roles');
    }

    /**
     * ELIMINADAS: registro(), conductor(), superadmin(), cliente()
     * Ya no existen como modelos separados relacionados a usuario.
     * El cliente ahora es una entidad completamente independiente.
     *
     * NOTA: Si en algún controlador hacías $usuario->registro->nom_reg,
     * ahora debes usar $usuario->nom_usu directamente.
     */
    
}