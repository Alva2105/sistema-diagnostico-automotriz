<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'cod_pagos';
    public $timestamps = false;
 
    protected $fillable = [
        'mon_pag',          // Monto pagado
        'mtp_pag',          // Método: EFECTIVO o QR
        'fec_pag',          // Fecha y hora del pago
        'cod_facturas_pag', // FK a facturas
    ];
 
    protected $casts = [
        'fec_pag' => 'datetime',
    ];
 
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'cod_facturas_pag', 'cod_facturas');
    }
}