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
        Schema::create('facturas', function (Blueprint $table) {
            $table->increments('cod_facturas');
            $table->timestamp('fec_fac')->useCurrent();
            $table->string('nfa_fac', 20)->unique();
            $table->string('pto_fac', 10)->nullable();
            $table->unsignedInteger('cod_clientes_fac');
            $table->unsignedInteger('cod_entregas_fac')->unique();
 
            $table->foreign('cod_clientes_fac')->references('cod_clientes')->on('clientes');
            $table->foreign('cod_entregas_fac')->references('cod_entregas')->on('entregas');
        });
    }
 
    public function down(): void { Schema::dropIfExists('facturas'); }
};