<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();

            // HMAC-SHA256 del correo institucional (64 caracteres hex). El correo NO se guarda.
            $table->char('email_hash', 64)->unique();

            // Rol institucional
            $table->string('rol_institucional', 30);

            // Solo si el rol es Estudiante
            $table->string('facultad')->nullable();
            $table->string('programa_academico')->nullable();
            $table->unsignedTinyInteger('semestre')->nullable();
            $table->string('jornada', 30)->nullable();

            // Solo si el rol es Funcionario, Contratista u Otro
            $table->string('dependencia', 150)->nullable();

            // Caracterización sociodemográfica
            $table->unsignedTinyInteger('edad');
            $table->string('sexo', 40);
            $table->string('identidad_genero', 60);
            $table->string('orientacion_sexual', 60);
            $table->string('estado_civil', 40);
            $table->string('nivel_educativo', 60);
            $table->unsignedTinyInteger('estrato');
            $table->string('municipio_residencia', 100);

            // Constancia de aceptación del consentimiento informado
            $table->timestamp('consentimiento_at');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('participantes');
    }
};
