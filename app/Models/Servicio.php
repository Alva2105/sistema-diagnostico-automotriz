<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // La tabla no tiene timestamps de Laravel (created_at / updated_at)
    public $timestamps = false;

    protected $table      = 'servicios';
    protected $primaryKey = 'cod_servicios';
    public    $incrementing = false;
    protected $keyType    = 'string';

    protected $fillable = [
        'cod_servicios',
        'nom_ser',
        'pre_ser',
        'des_ser',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    /**
     * Órdenes de trabajo donde aparece este servicio.
     */
    public function ordenServicios()
    {
        return $this->hasMany(OrdenServicio::class, 'cod_servicios', 'cod_servicios');
    }
}