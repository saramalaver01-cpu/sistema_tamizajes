<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modulos', function (Blueprint $table) {
            // Escala de respuesta (ej: Nunca, Casi nunca, Algunas veces...)
            $table->json('opciones_escala')->nullable()->after('total_preguntas');

            // Solo usado por el Violentómetro: tipos de relación a evaluar
            $table->json('contextos')->nullable()->after('opciones_escala');
        });
    }

    public function down()
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn(['opciones_escala', 'contextos']);
        });
    }
};
