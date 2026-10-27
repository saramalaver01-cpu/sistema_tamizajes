<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('niveles_resultado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->string('clave', 40);
            $table->string('etiqueta', 100);
            $table->string('color', 20)->default('verde'); // verde | amarillo | naranja | rojo
            $table->unsignedTinyInteger('min_porcentaje'); // 0-100
            $table->unsignedTinyInteger('max_porcentaje'); // 0-100
            $table->text('resultado');
            $table->text('reflexiona');
            $table->json('recomendaciones');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('niveles_resultado');
    }
};
