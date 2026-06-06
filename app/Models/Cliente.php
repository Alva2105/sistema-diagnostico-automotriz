<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'cod_clientes';
    public $incrementing = false;   
    protected $keyType = 'string'; 
    public $timestamps = false;

    protected $fillable = [
        'cod_clientes',  //Se asigna manualmente con el CI en el controlador
        'nom_cli',       // Nombre
        'app_cli',       // Apellido paterno
        'apm_cli',       // Apellido materno, nullable
        'tel_cli',       // Teléfono
        'dir_cli',       // Dirección
        'email_cli',     // Email del cliente
        'img_cli',       // Imagen
        // ELIMINADOS: ci_cli (no existe en BD), est_cli, cod_usu
    ];

    // =========================================================
    // ACCESSOR: nombre completo
    // =========================================================

    /**
     * Antes se armaba con $cliente->usuario->registro->nom_reg etc.
     * Ahora todo está en el mismo modelo.
     */
    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nom_cli} {$this->app_cli} " . ($this->apm_cli ?? ''));
    }

    // =========================================================
    // RELACIONES
    // =========================================================

    /**
     * Un cliente puede tener muchos vehículos.
     * FK en vehiculos: cod_clientes_veh → PK: cod_clientes
     */
    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class, 'cod_clientes_veh', 'cod_clientes');
    }

    /**
     * Un cliente puede tener muchas solicitudes.
     * FK: cod_clientes_sol → PK: cod_clientes
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'cod_clientes_sol', 'cod_clientes');
    }

    /**
     * Un cliente puede tener muchas facturas.
     */
    public function facturas()
    {
        return $this->hasMany(Factura::class, 'cod_clientes_fac', 'cod_clientes');
    }

    /**
     * Un cliente puede tener muchas notificaciones.
     */
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'cod_clientes_not', 'cod_clientes');
    }

    /**
     * ELIMINADAS: usuario(), relación con Conductor
     */
}