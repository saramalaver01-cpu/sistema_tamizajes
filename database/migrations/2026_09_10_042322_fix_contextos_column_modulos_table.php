<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modulos', function (Blueprint $table) {
            // Esta era la columna JSON vieja que chocaba de nombre con la relación contextos()
            $table->dropColumn('contextos');

            // Bandera simple: ¿este módulo usa selección de contexto? (solo Violentómetro, por ahora)
            $table->boolean('tiene_contextos')->default(false)->after('opciones_escala');
        });
    }

    public function down()
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->json('contextos')->nullable();
            $table->dropColumn('tiene_contextos');
        });
    }
};
