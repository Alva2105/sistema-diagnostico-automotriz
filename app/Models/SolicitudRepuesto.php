<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class SolicitudRepuesto extends Model
{
    protected $table = 'solicitudes_repuestos';
    protected $primaryKey = 'cod_solicitudesrep';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
 
    protected $fillable = [
        'can_sol',           // Cantidad solicitada
        'fec_sol_rep',       // Fecha de la solicitud
        'cod_repuestos_sol', // FK a repuestos
        'cod_usuarios_sol',  // FK a usuarios (el técnico que solicita)
        'cod_seg',
    ];
 
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'cod_repuestos_sol', 'cod_repuestos');
    }
 
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuarios_sol', 'cod_usuarios');
    }

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class, 'cod_seg', 'cod_seg');
    }
}