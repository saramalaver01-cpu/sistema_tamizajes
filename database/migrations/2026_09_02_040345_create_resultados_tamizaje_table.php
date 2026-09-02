<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resultados_tamizaje', function (Blueprint $table) {
            $table->id();
            $table->string('modulo', 30);   // violentometro | bienestar_emocional | carga_academica
            $table->string('nivel', 30);
            $table->unsignedInteger('puntaje');
            $table->timestamp('creado_en')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resultados_tamizaje');
    }
};