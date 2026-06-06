<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->string('cod_seg', 10)->primary();         // SEG001, SEG002... (trigger)
            $table->string('cod_solicitudes_seg', 10);        // FK → solicitudes
            $table->string('cod_usuarios_seg');               // FK → usuarios (técnico)
            $table->timestamp('fcs_seg')->useCurrent();       // fecha y hora del seguimiento
            $table->string('tit_seg', 100)->nullable();       // título opcional
            $table->text('obs_seg')->nullable();              // observaciones del avance
            $table->timestamps();

            $table->foreign('cod_solicitudes_seg')
                  ->references('cod_solicitudes')
                  ->on('solicitudes')
                  ->onDelete('cascade');

            $table->foreign('cod_usuarios_seg')
                  ->references('cod_usuarios')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};