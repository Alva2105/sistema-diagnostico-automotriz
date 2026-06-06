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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->increments('cod_diagnosticos');
            $table->timestamp('fec_dia')->useCurrent();
            $table->text('des_dia');
            $table->unsignedInteger('cod_solicitudes_dia')->unique();
 
            $table->foreign('cod_solicitudes_dia')->references('cod_solicitudes')->on('solicitudes');
        });
    }
 
    public function down(): void { Schema::dropIfExists('diagnosticos'); }
};