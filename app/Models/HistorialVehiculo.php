<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class HistorialVehiculo extends Model
{
    protected $table = 'historial_vehiculo';
    protected $primaryKey = 'cod_historial';
    public $timestamps = false;
 
    protected $fillable = [
        'fec_his',
        'des_his',
        'cod_vehiculos_his',    // Parte 1 de FK compuesta a vehiculos
        'cod_clientes_his',     // Parte 2 de FK compuesta a vehiculos
        'cod_mantenimientos_his',
    ];
 
    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class, 'cod_mantenimientos_his', 'cod_mantenimientos');
    }
 
    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'cod_vehiculos_his', 'cod_vehiculos');
    }
}