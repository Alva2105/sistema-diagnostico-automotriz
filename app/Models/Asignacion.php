<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    protected $table = 'asignaciones';
    protected $primaryKey = 'cod_asignaciones';
    public $incrementing = false;
    protected $keyType    = 'string';
    public $timestamps = false;
 
    protected $fillable = [
        'cod_asignaciones',
        'fec_asi',
        'obs_asi',
        'cod_solicitudes_asi',
        'cod_usuarios_asi',
    ];
 
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'cod_solicitudes_asi', 'cod_solicitudes');
    }
 
    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuarios_asi', 'cod_usuarios');
    }
}