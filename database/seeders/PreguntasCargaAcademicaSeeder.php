<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Dimension;
use Illuminate\Database\Seeder;

class PreguntasCargaAcademicaSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('clave', 'carga_academica')->first();

        if (!$modulo) {
            $this->command->warn('No existe el módulo "carga_academica". Corre primero ModulosSeeder.');
            return;
        }

        $modulo->update([
            'opciones_escala' => ['Nunca', 'Casi nunca', 'Algunas veces', 'Frecuentemente', 'Siempre'],
        ]);

        $dimensiones = [
            [
                'titulo' => '1. Organización y Manejo de Tiempo',
                'descripcion' => 'Evalúa tus hábitos de planificación y priorización de responsabilidades académicas frente a la procrastinación.',
                'preguntas' => [
                    '¿Te resulta difícil organizar tus actividades académicas durante la semana?',
                    '¿Sientes que el tiempo no te alcanza para cumplir con todas tus responsabilidades académicas?',
                    '¿Postergas con frecuencia la realización de tus tareas o trabajos?',
                ],
            ],
            [
                'titulo' => '2. Exigencia Académica Percibida',
                'descripcion' => 'Analiza la presión ejercida por la cantidad de trabajos y la carga emocional por plazos o calificaciones.',
                'preguntas' => [
                    '¿Consideras que la cantidad de tareas y trabajos asignados es excesiva?',
                    '¿Sientes presión constante por obtener buenas calificaciones?',
                    '¿Los plazos de entrega de trabajos te generan ansiedad?',
                ],
            ],
            [
                'titulo' => '3. Fatiga Física y Mental',
                'descripcion' => 'Mide los síntomas de cansancio corporal, tensión general y déficit de horas reparadoras de sueño.',
                'preguntas' => [
                    '¿Te sientes agotado/a física o mentalmente al finalizar la jornada académica?',
                    '¿Has experimentado dolores de cabeza, tensión muscular o problemas digestivos relacionados con la carga académica?',
                    '¿Sientes que necesitas más horas de descanso de las que puedes permitirte?',
                ],
            ],
            [
                'titulo' => '4. Concentración y Rendimiento Académico',
                'descripcion' => 'Revisa tu capacidad cognitiva de asimilación de conceptos y la autopercepción de desempeño en clase.',
                'preguntas' => [
                    '¿Te cuesta mantener la concentración durante las clases o al estudiar?',
                    '¿Has notado una disminución en tu rendimiento académico?',
                    '¿Sientes que comprendes los contenidos de las asignaturas que cursas?',
                ],
            ],
            [
                'titulo' => '5. Equilibrio entre Vida Académica y Personal',
                'descripcion' => 'Explora cómo las obligaciones de estudio repercuten en el ámbito familiar, social y los momentos de ocio.',
                'preguntas' => [
                    '¿Sacrificas tiempo con familia o amigos por actividades académicas?',
                    '¿Sientes que tu vida personal se ve afectada por las exigencias académicas?',
                    '¿Logras dedicar tiempo a actividades de ocio o recreación?',
                ],
            ],
            [
                'titulo' => '6. Bienestar Emocional asociado a la Actividad Académica',
                'descripcion' => 'Mide la frustración ante resultados, ideación de deserción escolar e índice de satisfacción del aprendizaje.',
                'preguntas' => [
                    '¿Te sientes frustrado/a con frecuencia por tu desempeño académico?',
                    '¿Has pensado en abandonar tus estudios debido a la carga académica?',
                    '¿Sientes satisfacción con lo que estás aprendiendo actualmente?',
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
