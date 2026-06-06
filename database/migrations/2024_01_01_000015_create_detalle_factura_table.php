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
        Schema::create('detalle_factura', function (Blueprint $table) {
            $table->increments('cod_detalle');
            $table->string('con_det', 150);
            $table->integer('can_det');
            $table->decimal('pun_det', 10, 2);
            $table->unsignedInteger('cod_facturas_det');
            $table->unsignedInteger('cod_repuestos_det')->nullable();
 
            $table->foreign('cod_facturas_det')->references('cod_facturas')->on('facturas');
            $table->foreign('cod_repuestos_det')->references('cod_repuestos')->on('repuestos');
        });
    }
 
    public function down(): void { Schema::dropIfExists('detalle_factura'); }
};