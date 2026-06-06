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
 
// CAMBIO: antes cod_rol era PK. Ahora es cod_roles (SERIAL = auto-increment en PG).
// Se elimina des_rol (no existe en nueva BD).
return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('cod_roles');            // antes: cod_rol
            $table->string('nom_rol', 50)->unique();
            // des_rol eliminado
        });
 
        // Seed inicial de roles
        \DB::table('roles')->insert([
            ['nom_rol' => 'SuperAdmin'],
            ['nom_rol' => 'Gerente'],
            ['nom_rol' => 'Cliente'],
            ['nom_rol' => 'TecnicoAutomotriz'],
            // Conductor eliminado
        ]);
    }
 
    public function down(): void { Schema::dropIfExists('roles'); }
};