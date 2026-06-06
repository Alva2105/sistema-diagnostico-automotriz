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
        Schema::create('kardex', function (Blueprint $table) {
            $table->increments('cod_kardex');
            $table->date('fec_kar')->default(DB::raw('CURRENT_DATE'));
            $table->string('num_kar', 20)->unique();
            $table->text('det_kar')->nullable();
            $table->integer('cod_vehiculos_kar');
            $table->unsignedInteger('cod_clientes_kar');
            $table->unsignedInteger('cod_mantenimientos_kar')->unique();
            $table->onDelete('cascade');
            $table->foreign(['cod_vehiculos_kar', 'cod_clientes_kar'])
                  ->references(['cod_vehiculos', 'cod_clientes_veh'])
                  ->on('vehiculos');
            $table->foreign('cod_mantenimientos_kar')->references('cod_mantenimientos')->on('mantenimientos');
        });
    }
 
    public function down(): void { Schema::dropIfExists('kardex'); }
};