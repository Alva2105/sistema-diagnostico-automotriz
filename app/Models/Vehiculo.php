<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * CAMBIOS RESPECTO A LA BD ANTERIOR:
 * - Tabla: "vehiculos" (sin cambio)
 * - PK COMPUESTA: (cod_vehiculos varchar(8), cod_clientes_veh varchar(20))
 *   cod_vehiculos = la PLACA del vehículo (se asigna desde el controlador)
 *   cod_clientes_veh = el CI del cliente dueño (FK a clientes)
 * - $incrementing = false y $keyType = 'string' porque la PK es varchar
 * - Laravel no soporta PKs compuestas nativamente en Eloquent.
 *   Para find() usar: Vehiculo::where('cod_vehiculos', $placa)
 *                                ->where('cod_clientes_veh', $ci)->first()
 * - Se ELIMINA "pla_veh" del fillable, no existe en la BD.
 *   La placa ES el cod_vehiculos, se asigna desde el controlador.
 * - Se ELIMINA el método siguienteCodigo() porque ya no aplica.
 * - Se ELIMINA "cod_con" (conductor) porque Conductor ya no existe.
 * - Se ELIMINA "est_veh" (estado del vehículo), no está en la nueva BD.
 * - Campo "anf_veh" (año) pasa a "ani_veh".
 * - Se ELIMINA "cod_man" (relación directa con mantenimiento desde vehículo).
 *
 * IMPACTO EN CÓDIGO:
 * - Vehiculo::create() debe incluir SIEMPRE cod_vehiculos (la placa)
 *   y cod_clientes_veh (el CI del cliente dueño).
 * - Para actualizar/eliminar siempre usar ambas partes de la PK.
 */
class Vehiculo extends Model
{
    use HasFactory;

    protected $table = 'vehiculos';
    protected $primaryKey = 'cod_vehiculos'; // Primera parte de la PK compuesta
    public $incrementing = false;            // PK varchar, no autoincremental
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'cod_vehiculos',    // Placa — parte 1 de la PK compuesta
        'cod_clientes_veh', // CI del cliente — parte 2 de la PK compuesta
        'mar_veh',          // Marca
        'mod_veh',          // Modelo
        'ani_veh',          // Año (antes era "anf_veh")
        'col_veh',          // Color
        'tip_veh',          // Tipo de vehículo
        // ELIMINADOS: pla_veh (no existe), cod_con, est_veh, cod_man
    ];

    // =========================================================
    // RELACIONES
    // =========================================================

    /**
     * Un vehículo pertenece a un cliente.
     * FK: cod_clientes_veh → PK de clientes: cod_clientes (el CI)
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cod_clientes_veh', 'cod_clientes');
    }

    /**
     * Un vehículo puede tener muchas solicitudes.
     */
    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'cod_vehiculos_sol', 'cod_vehiculos');
    }

    /**
     * Un vehículo puede tener muchos registros de historial.
     */
    public function historial()
    {
        return $this->hasMany(HistorialVehiculo::class, 'cod_vehiculos_his', 'cod_vehiculos');
    }

    /**
     * Un vehículo puede tener muchos registros de kardex.
     */
    public function kardex()
    {
        return $this->hasMany(Kardex::class, 'cod_vehiculos_kar', 'cod_vehiculos');
    }

    /**
     * ELIMINADAS: conductor(), mantenimiento() directo, siguienteCodigo()
     */
}