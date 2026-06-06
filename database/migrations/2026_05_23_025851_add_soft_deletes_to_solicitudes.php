<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para agregar borrado lógico a la tabla solicitudes.
 *
 * Ejecutar con:
 *   php artisan migrate
 *
 * Para revertir:
 *   php artisan migrate:rollback
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            /*
             * softDeletes() agrega la columna `deleted_at` (timestamp nullable).
             * Cuando un registro es "eliminado", Eloquent escribe aquí la fecha/hora
             * en lugar de hacer un DELETE físico.
             * Las consultas normales (::all(), ::find(), etc.) ignoran estos registros
             * automáticamente gracias al trait SoftDeletes en el Model.
             */
            $table->softDeletes();   // columna: deleted_at TIMESTAMP NULL DEFAULT NULL
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropSoftDeletes();   // elimina la columna deleted_at
        });
    }
};