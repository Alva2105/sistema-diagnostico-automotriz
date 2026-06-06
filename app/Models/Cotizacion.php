<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';
    protected $primaryKey = 'cod_cotizaciones';
    public $timestamps = false;
 
    protected $fillable = [
        'mon_cot',              // Monto total de la cotización
        'est_cot',              // Estado: PENDIENTE, APROBADA, RECHAZADA
        'cod_diagnosticos_cot', // FK única a diagnóstico (1:1)
    ];
 
    public function diagnostico()
    {
        return $this->belongsTo(Diagnostico::class, 'cod_diagnosticos_cot', 'cod_diagnosticos');
    }
 
    public function mantenimiento()
    {
        return $this->hasOne(Mantenimiento::class, 'cod_cotizaciones_man', 'cod_cotizaciones');
    }
}