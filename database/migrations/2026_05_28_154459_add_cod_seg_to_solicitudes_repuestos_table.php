<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes_repuestos', function (Blueprint $table) {
            $table->string('cod_seg', 10)->nullable()->after('cod_usuarios_sol');

            $table->foreign('cod_seg')
                  ->references('cod_seg')
                  ->on('seguimientos')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes_repuestos', function (Blueprint $table) {
            $table->dropForeign(['cod_seg']);
            $table->dropColumn('cod_seg');
        });
    }
};