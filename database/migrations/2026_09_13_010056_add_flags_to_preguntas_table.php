<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            // Para preguntas redactadas en positivo (ej: "sientes satisfacción")
            $table->boolean('invertida')->default(false)->after('texto');
            // Si se responde con el valor máximo de la escala, fuerza el nivel más alto
            $table->boolean('critica')->default(false)->after('invertida');
        });
    }

    public function down()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->dropColumn(['invertida', 'critica']);
        });
    }
};
