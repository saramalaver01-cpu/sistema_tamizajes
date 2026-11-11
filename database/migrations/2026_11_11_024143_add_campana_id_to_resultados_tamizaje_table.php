<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('resultados_tamizaje', function (Blueprint $table) {
            // Nullable: los resultados que no vienen de una campaña quedan en null
            $table->foreignId('campana_id')
                ->nullable()
                ->after('modulo')
                ->constrained('campanas')
                ->nullOnDelete();   // si borras la campaña, los resultados se conservan
        });
    }

    public function down()
    {
        Schema::table('resultados_tamizaje', function (Blueprint $table) {
            $table->dropConstrainedForeignId('campana_id');
        });
    }
};
