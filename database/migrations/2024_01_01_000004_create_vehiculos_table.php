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
        Schema::create('vehiculos', function (Blueprint $table) {
            // PK compuesta - no se usa increments(), se define manualmente
            $table->integer('cod_vehiculos');
            $table->unsignedInteger('cod_clientes_veh');
            $table->string('pla_veh', 20)->unique();
            $table->string('mar_veh', 50);
            $table->string('mod_veh', 50);
            $table->integer('ani_veh')->nullable();             // antes anf_veh
            $table->string('col_veh', 30)->nullable();
            $table->string('tip_veh', 50)->nullable();
            // ELIMINADOS: cod_con, est_veh, cod_man
 
            $table->primary(['cod_vehiculos', 'cod_clientes_veh']); // PK COMPUESTA
            $table->foreign('cod_clientes_veh')->references('cod_clientes')->on('clientes');
        });
    }
 
    public function down(): void { Schema::dropIfExists('vehiculos'); }
};