<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;   // ← NUEVO

class Solicitud extends Model
{
    use HasFactory;
    use SoftDeletes;   // ← activa el borrado lógico (columna deleted_at)

    protected $table      = 'solicitudes';
    protected $primaryKey = 'cod_solicitudes';
    public    $incrementing = false;
    protected $keyType    = 'string';
    public    $timestamps   = false;   // sin created_at/updated_at automáticos

    /*
     * SoftDeletes necesita que la columna deleted_at exista en la tabla.
     * Si tu tabla NO tiene timestamps automáticos (timestamps = false),
     * Laravel igual gestiona deleted_at manualmente.
     * Asegúrate de haber corrido la migración que agrega deleted_at (ver abajo).
     */

    protected $fillable = [
        'fec_sol',
        'obs_sol',
        'ser_sol',
        'fpr_sol',
        'hpr_sol',
        'tma_sol',
        'est_sol',
        'cod_clientes_sol',
        'cod_vehiculos_sol',
    ];

    protected $dates = ['deleted_at', 'restored_at'];   // para que Carbon lo parsee correctamente

    /* ──────────────────────────────────────────────
     |  RELACIONES
     ────────────────────────────────────────────── */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cod_clientes_sol', 'cod_clientes');
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'cod_vehiculos_sol', 'cod_vehiculos');
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacion::class, 'cod_solicitudes_asi', 'cod_solicitudes');
    }

    public function diagnostico()
    {
        return $this->hasOne(Diagnostico::class, 'cod_solicitudes_dia', 'cod_solicitudes');
    }
}