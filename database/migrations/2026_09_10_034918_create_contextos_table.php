<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contextos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('modulos')->onDelete('cascade');
            $table->string('valor', 40);      // slug interno, ej: "jefe"
            $table->string('etiqueta', 100);  // texto visible, ej: "Relación con un jefe"
            $table->string('icono', 50)->default('fa-user');
            $table->string('sujeto', 150);    // ej: "tu jefe" (reemplaza {sujeto} en las preguntas)
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->unique(['modulo_id', 'valor']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('contextos');
    }
};
