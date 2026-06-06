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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->increments('cod_asignaciones');
            $table->date('fec_asi')->default(DB::raw('CURRENT_DATE'));
            $table->text('obs_asi')->nullable();
            $table->unsignedInteger('cod_solicitudes_asi');
            $table->unsignedInteger('cod_usuarios_asi');
 
            $table->unique(['cod_solicitudes_asi', 'cod_usuarios_asi']);
            $table->foreign('cod_solicitudes_asi')->references('cod_solicitudes')->on('solicitudes');
            $table->foreign('cod_usuarios_asi')->references('cod_usuarios')->on('usuarios');
        });
    }
 
    public function down(): void { Schema::dropIfExists('asignaciones'); }
};