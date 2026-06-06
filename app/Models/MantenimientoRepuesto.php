<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MantenimientoRepuesto extends Model
{
    public $timestamps   = false;
    public $incrementing = false;

    protected $table      = 'mantenimiento_repuestos';
    protected $primaryKey = null;

    protected $fillable = [
        'cod_mantenimientos',
        'cod_repuestos',
        'cantidad',
    ];

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'cod_mantenimientos', 'cod_mantenimientos');
    }

    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'cod_repuestos', 'cod_repuestos');
    }
}