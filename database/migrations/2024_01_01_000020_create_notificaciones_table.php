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
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->increments('cod_notificaciones');
            $table->string('tip_not', 30); // AVANCE_TRABAJO|FACTURA|ALERTA_STOCK|ENTREGA
            $table->text('men_not');
            $table->timestamp('fec_not')->useCurrent();
            $table->boolean('lei_not')->default(false);
            $table->unsignedInteger('cod_usuarios_not')->nullable();
            $table->unsignedInteger('cod_clientes_not')->nullable();
 
            $table->foreign('cod_usuarios_not')->references('cod_usuarios')->on('usuarios');
            $table->foreign('cod_clientes_not')->references('cod_clientes')->on('clientes');
        });
    }
 
    public function down(): void { Schema::dropIfExists('notificaciones'); }
};