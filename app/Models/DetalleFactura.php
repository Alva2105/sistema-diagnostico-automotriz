<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    protected $table = 'detalle_factura';
    protected $primaryKey = 'cod_detalle';
    public $timestamps = false;
 
    protected $fillable = [
        'con_det',          // Concepto (ej: "Cambio de aceite", "Filtro de aire")
        'can_det',          // Cantidad
        'pun_det',          // Precio unitario al momento de facturar
        'cod_facturas_det', // FK a facturas
        'cod_repuestos_det',// FK a repuestos (opcional, si es un repuesto)
    ];
 
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'cod_facturas_det', 'cod_facturas');
    }
 
    public function repuesto()
    {
        return $this->belongsTo(Repuesto::class, 'cod_repuestos_det', 'cod_repuestos');
    }
 
    public function getSubtotalAttribute(): float
    {
        return $this->can_det * $this->pun_det;
    }
}