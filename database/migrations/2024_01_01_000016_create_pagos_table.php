<?php
 
// =====================================================================
// INSTRUCCIONES DE USO:
// Coloca cada clase en un archivo separado dentro de database/migrations/
// con el nombre: YYYY_MM_DD_HHMMSS_nombre.php
// El orden de los archivos importa (por las FKs).
// =====================================================================
 
// ─────────────────────────────────────────────────────────────────────
// 2024_01_01_000001_create_roles_table.php
// ─────────────────────────────────────────────────────────────────────
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->increments('cod_pagos');
            $table->decimal('mon_pag', 10, 2);
            $table->string('mtp_pag', 20); // EFECTIVO | QR
            $table->timestamp('fec_pag')->useCurrent();
            $table->unsignedInteger('cod_facturas_pag');
 
            $table->foreign('cod_facturas_pag')->references('cod_facturas')->on('facturas');
        });
    }
 
    public function down(): void { Schema::dropIfExists('pagos'); }
};