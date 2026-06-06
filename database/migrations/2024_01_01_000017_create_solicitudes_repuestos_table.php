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
        Schema::create('solicitudes_repuestos', function (Blueprint $table) {
            $table->increments('cod_solicitudesrep');
            $table->integer('can_sol');
            $table->date('fec_sol_rep')->default(DB::raw('CURRENT_DATE'));
            $table->unsignedInteger('cod_repuestos_sol');
            $table->unsignedInteger('cod_usuarios_sol');
 
            $table->foreign('cod_repuestos_sol')->references('cod_repuestos')->on('repuestos');
            $table->foreign('cod_usuarios_sol')->references('cod_usuarios')->on('usuarios');
        });
    }
 
    public function down(): void { Schema::dropIfExists('solicitudes_repuestos'); }
};