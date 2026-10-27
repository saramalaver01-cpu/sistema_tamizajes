<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 40)->unique(); // violentometro, bienestar_emocional, carga_academica
            $table->string('nombre', 100);
            $table->text('descripcion');
            $table->string('icono', 50)->default('fa-shield-heart'); // clase de FontAwesome
            $table->string('color', 20)->default('green'); // green | purple | blue (define el gradiente de la tarjeta)
            $table->unsignedTinyInteger('tiempo_estimado')->default(5); // minutos
            $table->unsignedInteger('total_preguntas')->default(0);
            $table->boolean('activo')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('modulos');
    }
};
