<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    protected $table = 'entregas';
    protected $primaryKey = 'cod_entregas';
    public $timestamps = false;
 
    protected $fillable = [
        'fec_ent',              // Fecha de entrega
        'obs_ent',              // Observaciones de la entrega
        'cod_mantenimientos_ent', // FK única a mantenimiento (1:1)
    ];
 
    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'cod_mantenimientos_ent', 'cod_mantenimientos');
    }
 
    public function factura()
    {
        return $this->hasOne(Factura::class, 'cod_entregas_fac', 'cod_entregas');
    }
}