<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $table = 'diagnosticos';
    protected $primaryKey = 'cod_diagnosticos';
    public $timestamps = false;
 
    protected $fillable = [
        'fec_dia',              // Fecha y hora del diagnóstico
        'des_dia',              // Descripción del diagnóstico
        'cod_solicitudes_dia',  // FK única a solicitudes (1:1)
    ];
 
    protected $casts = [
        'fec_dia' => 'datetime',
    ];
 
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'cod_solicitudes_dia', 'cod_solicitudes');
    }
 
    public function cotizacion()
    {
        return $this->hasOne(Cotizacion::class, 'cod_diagnosticos_cot', 'cod_diagnosticos');
    }
}