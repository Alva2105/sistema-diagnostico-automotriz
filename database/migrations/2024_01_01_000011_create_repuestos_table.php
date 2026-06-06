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
        Schema::create('repuestos', function (Blueprint $table) {
            $table->increments('cod_repuestos');
            $table->string('nom_rep', 100)->unique();
            $table->decimal('pre_rep', 10, 2)->default(0);            // NUEVO: precio unitario
            $table->integer('stock')->default(0);                      // antes cant_rep
            $table->unsignedInteger('cod_categoria_rep')->nullable();  // antes cat_rep (string)
            // Parámetros teoría de inventarios
            $table->integer('dda_rep')->default(0)->nullable();
            $table->integer('dan_rep')->default(0)->nullable();
            $table->integer('cmi_rep')->default(0)->nullable();
            $table->integer('cse_rep')->default(0)->nullable();
            $table->integer('nre_rep')->default(0)->nullable();
            $table->integer('cop_rep')->default(0)->nullable();
            $table->integer('cma_rep')->default(0)->nullable();
            $table->integer('tle_rep')->default(0)->nullable();
            $table->decimal('cor_rep', 10, 2)->default(0)->nullable();
            $table->decimal('cal_rep', 10, 2)->default(0)->nullable();
            // ELIMINADOS: cod_inv, cat_rep, img_rep, est_rep, mar_rep, mod_rep, fma_rep, fsa_rep
 
            $table->foreign('cod_categoria_rep')->references('cod_categorias')->on('categorias_repuestos');
        });
    }
 
    public function down(): void { Schema::dropIfExists('repuestos'); }
};