<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $table = 'kardex';
    protected $primaryKey = 'cod_kardex';
    public $timestamps = false;
 
    protected $fillable = [
        'fec_kar',
        'num_kar',               // Número único de kardex
        'det_kar',               // Detalle
        'cod_vehiculos_kar',     // FK compuesta parte 1
        'cod_clientes_kar',      // FK compuesta parte 2
        'cod_mantenimientos_kar',
    ];
 
    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'cod_mantenimientos_kar', 'cod_mantenimientos');
    }
 
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'cod_vehiculos_kar', 'cod_vehiculos');
    }
}