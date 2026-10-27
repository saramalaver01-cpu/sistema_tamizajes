<?php

namespace Database\Seeders;

use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModulosSeeder extends Seeder
{
    public function run()
    {
        Modulo::updateOrCreate(
            ['clave' => 'violentometro'],
            [
                'nombre' => 'Violentómetro',
                'descripcion' => 'Identifica diferentes manifestaciones de violencia y conoce cuándo buscar ayuda.',
                'icono' => 'fa-shield-heart',
                'color' => 'green',
                'tiempo_estimado' => 5,
                'total_preguntas' => 21,
                'activo' => true,
                'orden' => 1,
            ]
        );

        Modulo::updateOrCreate(
            ['clave' => 'bienestar_emocional'],
            [
                'nombre' => 'Bienestar emocional',
                'descripcion' => 'Responde el tamizaje para conocer aspectos relacionados con tu bienestar emocional.',
                'icono' => 'fa-face-smile',
                'color' => 'purple',
                'tiempo_estimado' => 5,
                'total_preguntas' => 21,
                'activo' => true,
                'orden' => 2,
            ]
        );

        Modulo::updateOrCreate(
            ['clave' => 'carga_academica'],
            [
                'nombre' => 'Carga académica',
                'descripcion' => 'Identifica cómo la carga académica puede estar influyendo en tu experiencia universitaria.',
                'icono' => 'fa-book-open',
                'color' => 'blue',
                'tiempo_estimado' => 5,
                'total_preguntas' => 18,
                'activo' => true,
                'orden' => 3,
            ]
        );
    }
}
