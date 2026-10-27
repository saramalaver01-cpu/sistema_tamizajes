<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Dimension;
use Illuminate\Database\Seeder;

class PreguntasBienestarEmocionalSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('clave', 'bienestar_emocional')->first();

        if (!$modulo) {
            $this->command->warn('No existe el módulo "bienestar_emocional". Corre primero ModulosSeeder.');
            return;
        }

        $modulo->update([
            'opciones_escala' => ['Nunca', 'Casi nunca', 'Algunas veces', 'Frecuentemente', 'Siempre'],
        ]);

        $dimensiones = [
            [
                'titulo' => '1. Estado de Ánimo',
                'descripcion' => 'Explora la presencia de sentimientos de tristeza, variaciones anímicas y satisfacción general en tus últimas semanas.',
                'preguntas' => [
                    '¿Te has sentido triste o desanimado/a sin una razón aparente?',
                    '¿Has experimentado cambios bruscos en tu estado de ánimo?',
                    '¿Has sentido satisfacción con tu vida en general?',
                ],
            ],
            [
                'titulo' => '2. Regulación Emocional',
                'descripcion' => 'Evalúa tu capacidad de controlar e identificar tus emociones ante situaciones adversas o de alta frustración.',
                'preguntas' => [
                    '¿Te resulta difícil controlar tus emociones en situaciones de presión?',
                    '¿Has reaccionado de forma impulsiva ante situaciones que te generan frustración?',
                    '¿Logras identificar lo que sientes y expresarlo adecuadamente?',
                ],
            ],
            [
                'titulo' => '3. Estrés Percibido',
                'descripcion' => 'Mide el nivel de sobrecarga por responsabilidades y las repercusiones físicas del estrés acumulado.',
                'preguntas' => [
                    '¿Te has sentido abrumado/a por las responsabilidades diarias?',
                    '¿Has sentido que no puedes manejar todas las cosas que tienes que hacer?',
                    '¿Has experimentado tensión física (dolor de cabeza, espalda) por estrés?',
                ],
            ],
            [
                'titulo' => '4. Energía y Descanso',
                'descripcion' => 'Analiza la calidad del sueño, la facilidad para dormir y los niveles diarios de energía vital.',
                'preguntas' => [
                    '¿Te sientes cansado/a incluso después de dormir?',
                    '¿Tienes dificultades para conciliar el sueño o mantenerte dormido/a?',
                    '¿Sientes que tienes energía suficiente para realizar tus actividades diarias?',
                ],
            ],
            [
                'titulo' => '5. Motivación',
                'descripcion' => 'Revisa tu nivel de interés por pasatiempos, metas a largo plazo y la facilidad de iniciar tareas.',
                'preguntas' => [
                    '¿Has perdido interés en actividades que antes disfrutabas?',
                    '¿Te cuesta encontrar motivación para iniciar tus tareas diarias?',
                    '¿Sientes que tus metas personales y académicas tienen sentido?',
                ],
            ],
            [
                'titulo' => '6. Relaciones Interpersonales',
                'descripcion' => 'Explora la percepción de soledad, frecuencia de desacuerdos y confianza con las personas que te rodean.',
                'preguntas' => [
                    '¿Te has sentido solo/a o aislado/a de las personas que te rodean?',
                    '¿Has tenido conflictos frecuentes con personas cercanas a ti?',
                    '¿Sientes que puedes confiar en las personas de tu entorno?',
                ],
            ],
            [
                'titulo' => '7. Apoyo Social',
                'descripcion' => 'Identifica tus redes de respaldo externo e institucional en momentos de vulnerabilidad.',
                'preguntas' => [
                    '¿Cuentas con personas a quienes puedes recurrir cuando necesitas ayuda?',
                    '¿Sientes que la institución te brinda espacios de apoyo emocional?',
                    '¿Te sientes respaldado/a por tu familia, amigos o compañeros?',
                ],
            ],
        ];

        foreach ($dimensiones as $ordenDim => $dim) {
            $dimension = Dimension::updateOrCreate(
                ['modulo_id' => $modulo->id, 'titulo' => $dim['titulo']],
                ['descripcion' => $dim['descripcion'], 'orden' => $ordenDim]
            );

            foreach ($dim['preguntas'] as $ordenPregunta => $texto) {
                $dimension->preguntas()->updateOrCreate(
                    ['texto' => $texto],
                    ['orden' => $ordenPregunta]
                );
            }
        }

        $modulo->actualizarTotalPreguntas();
    }
}
