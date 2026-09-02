<?php

namespace App\Http\Controllers;
use App\Models\ResultadoTamizaje;
use Illuminate\Http\Request;

class BienestarEmocionalController extends Controller
{
    public function index()
    {
        return view('layouts.Bienestar-emocional');
    }

    public function evaluar(Request $request)
    {
        $validated = $request->validate([
            'respuestas'   => ['required', 'array', 'size:21'],
            'respuestas.*' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $respuestas = $validated['respuestas'];

        /*
         * =========================================================
         * INVERSIÓN DE PREGUNTAS REDACTADAS EN POSITIVO
         * =========================================================
         * Estas preguntas suman bienestar cuando la frecuencia es alta
         * (ej. "sientes satisfacción"), así que se invierten (6 - valor)
         * para que, igual que las demás, un valor alto siempre
         * signifique más malestar.
         *
         * Índices (0-based): 2, 5, 11, 14, 17, 18, 19, 20
         */

        $indicesPositivos = [2, 5, 11, 14, 17, 18, 19, 20];

        $puntaje = 0;

        foreach ($respuestas as $i => $valor) {
            $puntaje += in_array($i, $indicesPositivos) ? (6 - $valor) : $valor;
        }

        /*
         * Rango total: 21 (mejor bienestar posible) a 105 (peor).
         */

        if ($puntaje <= 42) {
            $nivel = 'fortalecido';
        } elseif ($puntaje <= 63) {
            $nivel = 'estable';
        } elseif ($puntaje <= 84) {
            $nivel = 'seguimiento';
        } else {
            $nivel = 'atencion';
        }

        $niveles = [
            'fortalecido' => [
                'etiqueta' => 'Bienestar fortalecido',
                'color'    => 'verde',
                'resultado' => 'Presentas indicadores favorables de bienestar emocional.',
                'reflexiona' => 'Mantener hábitos saludables y relaciones positivas constituye un factor protector para tu salud mental.',
                'recomendaciones' => [
                    'Continúa fortaleciendo tus estrategias de autocuidado.',
                    'Conserva y fortalece tus redes de apoyo.',
                    'Mantén actividades que favorezcan tu bienestar.',
                    'Comparte prácticas saludables con otras personas.',
                ],
            ],
            'estable' => [
                'etiqueta' => 'Bienestar estable',
                'color'    => 'azul',
                'resultado' => 'Presentas indicadores adecuados de bienestar emocional, aunque existen aspectos susceptibles de fortalecimiento.',
                'reflexiona' => 'El bienestar emocional es dinámico y requiere acciones permanentes de autocuidado.',
                'recomendaciones' => [
                    'Mantén hábitos saludables.',
                    'Fortalece tus redes de apoyo.',
                    'Realiza actividades de bienestar.',
                    'Continúa monitoreando tu salud emocional.',
                ],
            ],
            'seguimiento' => [
                'etiqueta' => 'Bienestar en seguimiento',
                'color'    => 'amarillo',
                'resultado' => 'Se identifican algunos factores que podrían estar afectando tu bienestar y calidad de vida.',
                'reflexiona' => 'El bienestar emocional requiere atención constante y estrategias de cuidado personal.',
                'recomendaciones' => [
                    'Fortalece hábitos saludables.',
                    'Busca espacios de recreación y descanso.',
                    'Comparte tus emociones con personas de confianza.',
                    'Considera solicitar orientación profesional.',
                ],
            ],
            'atencion' => [
                'etiqueta' => 'Bienestar que requiere atención',
                'color'    => 'naranja',
                'resultado' => 'Se identifican indicadores que podrían estar afectando significativamente tu bienestar emocional.',
                'reflexiona' => 'Tu bienestar emocional merece atención y cuidado. Buscar apoyo es una estrategia de protección y autocuidado.',
                'recomendaciones' => [
                    'Busca apoyo emocional o profesional.',
                    'Fortalece tus redes de apoyo.',
                    'Prioriza espacios de descanso y autocuidado.',
                    'Consulta los servicios institucionales disponibles.',
                ],
            ],
        ];

        ResultadoTamizaje::create([
         'modulo'    => 'bienestar_emocional',
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