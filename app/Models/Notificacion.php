<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    protected $table = 'notificaciones';
    protected $primaryKey = 'cod_notificaciones';
    public $timestamps = false;
 
    protected $fillable = [
        'tip_not',          // Tipo: AVANCE_TRABAJO, FACTURA, ALERTA_STOCK, ENTREGA
        'men_not',          // Mensaje de la notificación
        'fec_not',          // Fecha y hora
        'lei_not',          // Si fue leída (boolean)
        'cod_usuarios_not', // FK a usuarios (si va a un trabajador), nullable
        'cod_clientes_not', // FK a clientes (si va a un cliente), nullable
    ];
 
    protected $casts = [
        'fec_not' => 'datetime',
        'lei_not' => 'boolean',
    ];
 
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuarios_not', 'cod_usuarios');
    }
 
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cod_clientes_not', 'cod_clientes');
    }
}