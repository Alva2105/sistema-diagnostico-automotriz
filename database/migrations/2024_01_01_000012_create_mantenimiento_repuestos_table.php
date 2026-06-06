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
        Schema::create('mantenimiento_repuestos', function (Blueprint $table) {
            $table->unsignedInteger('cod_mantenimientos');
            $table->unsignedInteger('cod_repuestos');
            $table->integer('cantidad');
            $table->onDelete('cascade');
            $table->primary(['cod_mantenimientos', 'cod_repuestos']);
            $table->foreign('cod_mantenimientos')->references('cod_mantenimientos')->on('mantenimientos');
            $table->foreign('cod_repuestos')->references('cod_repuestos')->on('repuestos');
        });
    }
 
    public function down(): void { Schema::dropIfExists('mantenimiento_repuestos'); }
};