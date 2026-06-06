<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seguimiento extends Model
{
    use SoftDeletes;

    protected $table      = 'seguimientos';
    protected $primaryKey = 'cod_seg';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public $timestamps = true;

    const DELETE_AT = 'deleted_at';

    protected $fillable = [
        'cod_solicitudes_seg',
        'cod_usuarios_seg',
        'fcs_seg',
        'tit_seg',
        'obs_seg',
        'deleted_at',
        'restored_at',
    ];

    protected $casts = [
        'fcs_seg' => 'datetime',
        'deleted_at'  => 'datetime',
        'restored_at' => 'datetime',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'cod_solicitudes_seg', 'cod_solicitudes');
    }

    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'cod_usuarios_seg', 'cod_usuarios');
    }

    public function repuestosUsados()
    {
        return $this->hasMany(SolicitudRepuesto::class, 'cod_seg', 'cod_seg');
    }
   
}