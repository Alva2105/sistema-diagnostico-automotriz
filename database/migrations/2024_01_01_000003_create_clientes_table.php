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
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('cod_clientes');
            $table->string('ci_cli', 20)->unique();           // NUEVO: CI propio del cliente
            $table->string('nom_cli', 100);
            $table->string('app_cli', 100);
            $table->string('apm_cli', 100)->nullable();
            $table->string('tel_cli', 20)->nullable();
            $table->string('dir_cli', 150)->nullable();
            $table->string('email_cli', 150)->nullable();
            $table->text('img_cli')->nullable();
            // ELIMINADOS: est_cli, cod_usu (FK a usuario)
        });
    }
 
    public function down(): void { Schema::dropIfExists('clientes'); }
};