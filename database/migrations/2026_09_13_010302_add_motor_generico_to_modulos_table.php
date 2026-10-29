<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->boolean('motor_generico')->default(true)->after('total_preguntas');
        });
    }

    public function down()
    {
        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn('motor_generico');
        });
    }
};
