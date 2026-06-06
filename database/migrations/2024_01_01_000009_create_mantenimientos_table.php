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
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->increments('cod_mantenimientos');
            $table->text('des_man')->nullable();
            $table->timestamp('fec_ini_man')->useCurrent();
            $table->timestamp('fec_fin_man')->nullable();
            $table->decimal('htr_man', 10, 2)->nullable();
            $table->string('est_man', 20)->default('EN_PROCESO');    // EN_PROCESO|VERIFICACION|FINALIZADO
            $table->string('est_ver_man', 20)->nullable();           // APROBADO|RECHAZADO|PENDIENTE (NUEVO)
            $table->unsignedInteger('cod_cotizaciones_man')->unique();
 
            $table->foreign('cod_cotizaciones_man')->references('cod_cotizaciones')->on('cotizaciones');
        });
    }
 
    public function down(): void { Schema::dropIfExists('mantenimientos'); }
};