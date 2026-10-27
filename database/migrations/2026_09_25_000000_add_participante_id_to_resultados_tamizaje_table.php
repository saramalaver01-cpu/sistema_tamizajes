<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resultados_tamizaje', function (Blueprint $table) {

            // Nullable: los resultados guardados antes de este cambio no tienen participante asociado.
            $table->foreignId('participante_id')
                ->nullable()
                ->after('id')
                ->constrained('participantes')
                ->nullOnDelete();

            $table->index('participante_id');
        });
    }

    public function down()
    {
        Schema::table('resultados_tamizaje', function (Blueprint $table) {
            $table->dropConstrainedForeignId('participante_id');
        });
    }
};
