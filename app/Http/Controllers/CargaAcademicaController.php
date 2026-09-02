<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\ResultadoTamizaje;

class CargaAcademicaController extends Controller
{
    public function index()
    {
        return view('layouts.carga-academica');
    }

    public function evaluar(Request $request)
    {
        $validated = $request->validate([
            'respuestas'   => ['required', 'array', 'size:18'],
            'respuestas.*' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $respuestas = $validated['respuestas'];

        /*
         * =========================================================
         * INVERSIÓN DE PREGUNTAS REDACTADAS EN POSITIVO
         * =========================================================
         * Índices (0-based) 11, 14 y 17 preguntan por algo positivo
         * (comprensión, tiempo de ocio, satisfacción), así que se
         * invierten (6 - valor) para que un valor alto siempre
         * signifique más carga académica percibida.
         */

        $indicesPositivos = [11, 14, 17];

        $puntaje = 0;

        foreach ($respuestas as $i => $valor) {
            $puntaje += in_array($i, $indicesPositivos) ? (6 - $valor) : $valor;
        }

        /*
         * Rango total: 18 (carga mínima) a 90 (carga máxima).
         */

        if ($puntaje <= 36) {
            $nivel = 'baja';
        } elseif ($puntaje <= 54) {
            $nivel = 'moderada';
        } elseif ($puntaje <= 72) {
            $nivel = 'alta';
        } else {
            $nivel = 'critica';
        }

        $niveles = [
            'baja' => [
                'etiqueta' => 'Carga académica baja',
                'color'    => 'verde',
                'resultado' => 'Actualmente presentas una carga académica que parece estar siendo gestionada de manera adecuada, permitiéndote mantener un equilibrio entre tus responsabilidades y tu bienestar personal.',
                'reflexiona' => 'Mantener un equilibrio saludable entre las actividades académicas, el descanso y la vida personal es un factor protector para tu bienestar emocional y tu desempeño académico.',
                'recomendaciones' => [
                    'Continúa utilizando estrategias de organización que te funcionen.',
                    'Mantén espacios de descanso, recreación y autocuidado.',
                    'Aprovecha este momento para fortalecer hábitos saludables y técnicas de estudio.',
                    'Recuerda que el bienestar también se construye disfrutando de actividades fuera del ámbito académico.',
                ],
            ],
            'moderada' => [
                'etiqueta' => 'Carga académica moderada',
                'color'    => 'amarillo',
                'resultado' => 'Se identifican algunos factores asociados a una mayor exigencia académica que podrían afectar tu bienestar si se mantienen en el tiempo.',
                'reflexiona' => 'Sentirse ocasionalmente exigido es parte de la vida universitaria; sin embargo, es importante reconocer cuándo las responsabilidades comienzan a limitar el descanso, el bienestar o la vida personal.',
                'recomendaciones' => [
                    'Revisa la distribución de tu tiempo y establece prioridades realistas.',
                    'Programa espacios de descanso y actividades que disfrutes.',
                    'Evita acumular tareas y utiliza herramientas de planificación.',
                    'Hablar con compañeros, docentes o personas de confianza puede ayudarte a encontrar nuevas estrategias.',
                ],
            ],
            'alta' => [
                'etiqueta' => 'Carga académica alta',
                'color'    => 'naranja',
                'resultado' => 'Se identifican indicadores de sobrecarga académica que podrían estar afectando tu bienestar emocional, tu nivel de energía y tu capacidad de concentración.',
                'reflexiona' => 'La productividad no debe lograrse a costa de tu salud física o emocional. Sentirte constantemente agotado, preocupado o sin tiempo para ti puede ser una señal de que necesitas reajustar tus dinámicas académicas y personales.',
                'recomendaciones' => [
                    'Prioriza las actividades más importantes y evita la autoexigencia excesiva.',
                    'Establece horarios de estudio con pausas programadas.',
                    'Fortalece hábitos de sueño, alimentación y actividad física.',
                    'Busca apoyo académico o emocional si sientes que la carga está superando tus recursos personales.',
                ],
            ],
            'critica' => [
                'etiqueta' => 'Carga académica crítica',
                'color'    => 'rojo',
                'resultado' => 'Se identifican indicadores que sugieren una sobrecarga académica significativa, la cual podría estar afectando de manera importante tu bienestar y calidad de vida.',
                'reflexiona' => 'Ningún logro académico debería comprometer tu salud física, emocional o tus relaciones personales. Reconocer que necesitas apoyo es un paso importante para recuperar el equilibrio y cuidar de ti mismo(a).',
                'recomendaciones' => [
                    'Busca apoyo institucional, académico o psicológico lo antes posible.',
                    'Revisa tus responsabilidades y considera estrategias para redistribuir la carga.',
                    'Prioriza el descanso y las actividades básicas de autocuidado.',
                    'Habla con personas de confianza y evita afrontar esta situación en soledad.',
                ],
            ],
        ];

        ResultadoTamizaje::create([
            'modulo'    => 'carga_academica',
            'nivel'     => $nivel,
            'puntaje'   => $puntaje,
            'creado_en' => now(),
        ]);

        return response()->json(array_merge([
            'nivel'   => $nivel,
            'puntaje' => $puntaje,
        ], $niveles[$nivel]));
    }
}