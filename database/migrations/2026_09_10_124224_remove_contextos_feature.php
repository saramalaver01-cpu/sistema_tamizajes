<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('dimensiones', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contexto_id');
        });

        Schema::table('modulos', function (Blueprint $table) {
            $table->dropColumn('tiene_contextos');
        });

        Schema::dropIfExists('contextos');
    }

    public function down()
    {
        Schema::create('contextos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->string('valor', 40);
            $table->string('etiqueta', 100);
            $table->string('icono', 50)->default('fa-user');
            $table->string('sujeto', 150);
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();
            $table->unique(['modulo_id', 'valor']);
        });

        Schema::table('modulos', function (Blueprint $table) {
            $table->boolean('tiene_contextos')->default(false);
        });

        Schema::table('dimensiones', function (Blueprint $table) {
            $table->foreignId('contexto_id')->nullable()->constrained('contextos')->onDelete('cascade');
        });
    }
};
