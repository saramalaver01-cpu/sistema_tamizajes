<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('campanas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->string('codigo', 12)->unique();   // lo que va en el enlace y en el QR
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activa')->default(true);  // false = pausada
            $table->unsignedInteger('ingresos')->default(0); // veces que se abrió el enlace
            $table->timestamps();
        });

        // Qué módulos incluye cada campaña (relación muchos a muchos)
        Schema::create('campana_modulo', function (Blueprint $table) {
            $table->foreignId('campana_id')->constrained('campanas')->cascadeOnDelete();
            $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
            $table->primary(['campana_id', 'modulo_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('campana_modulo');
        Schema::dropIfExists('campanas');
    }
};
