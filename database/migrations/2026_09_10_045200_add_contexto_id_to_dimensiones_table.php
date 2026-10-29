<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dimensiones', function (Blueprint $table) {
            $table->foreignId('contexto_id')
                ->nullable()
                ->after('modulo_id')
                ->constrained('contextos')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('dimensiones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contexto_id');
        });
    }
};
