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
        Schema::create('historial_vehiculo', function (Blueprint $table) {
            $table->increments('cod_historial');
            $table->date('fec_his')->default(DB::raw('CURRENT_DATE'));
            $table->text('des_his')->nullable();
            $table->integer('cod_vehiculos_his');
            $table->unsignedInteger('cod_clientes_his');
            $table->unsignedInteger('cod_mantenimientos_his');
            $table->onDelete('cascade');
            $table->foreign(['cod_vehiculos_his', 'cod_clientes_his'])
                  ->references(['cod_vehiculos', 'cod_clientes_veh'])
                  ->on('vehiculos');
            $table->foreign('cod_mantenimientos_his')->references('cod_mantenimientos')->on('mantenimientos');
        });
    }
 
    public function down(): void { Schema::dropIfExists('historial_vehiculo'); }
};