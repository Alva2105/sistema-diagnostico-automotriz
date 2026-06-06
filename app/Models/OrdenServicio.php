<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    public $timestamps  = false;
    public $incrementing = false;

    protected $table    = 'orden_servicios';

    // Clave primaria compuesta — Laravel no la soporta nativamente,
    // se deshabilita para evitar que intente buscar por 'id'.
    protected $primaryKey = null;

    protected $fillable = [
        'cod_mantenimientos',
        'cod_servicios',
        'cantidad',
    ];

    // ── Relaciones ────────────────────────────────────────────────

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'cod_mantenimientos', 'cod_mantenimientos');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'cod_servicios', 'cod_servicios');
    }
}