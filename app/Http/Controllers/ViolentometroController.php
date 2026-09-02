<?php

namespace App\Http\Controllers;
use App\Models\ResultadoTamizaje;

use Illuminate\Http\Request;

class ViolentometroController extends Controller
{
    public function index()
{
    return view('layouts.violentometro');
}

    public function evaluar(Request $request)
    {
        $validated = $request->validate([
            'contexto'     => ['required', 'in:pareja,familia,amistad,companeros,laboral'],
            'respuestas'   => ['required', 'array', 'size:21'],
            'respuestas.*' => ['required', 'integer', 'min:1', 'max:5'],
        ]);

        $respuestas = $validated['respuestas'];
        $puntaje    = array_sum($respuestas);

        /*
         * =========================================================
         * ALGORITMO DE CLASIFICACIÓN (PROPUESTA — VALIDAR CON EL
         * EQUIPO PSICOSOCIAL ANTES DE USAR EN PRODUCCIÓN)
         * =========================================================
         *
         * Índices (0-based) de las preguntas más graves:
         *  14 -> amenaza con hacer daño
         *  17 -> violencia física (empujar/agredir)
         *  18 -> impedir salir / restringir movilidad
         *  19 -> presión para actos sexuales
         *  20 -> contacto físico/sexual sin consentimiento
         *
         * Regla: si alguna de estas se respondió "Siempre" (5),
         * el nivel es crítico sin importar el puntaje total.
         * Si alguna se respondió "Algunas veces" o más (>=3),
         * el nivel mínimo es alto.
         */

        $indicesGraves = [14, 17, 18, 19, 20];

        $maxGrave = 0;
        foreach ($indicesGraves as $i) {
            $maxGrave = max($maxGrave, $respuestas[$i]);
        }

        if ($maxGrave >= 5) {
            $nivel = 'critico';
        } elseif ($maxGrave >= 3 || $puntaje >= 85) {
            $nivel = 'alto';
        } elseif ($puntaje >= 64) {
            $nivel = 'alto';
        } elseif ($puntaje >= 43) {
            $nivel = 'medio';
        } else {
            $nivel = 'sin_riesgo';
        }

        $niveles = [
            'sin_riesgo' => [
                'etiqueta' => 'Sin riesgo',
                'color'    => 'blanco',
                'resultado' => 'No se identifican indicadores de riesgo asociados a situaciones de violencia en el contexto evaluado.',
                'reflexiona' => 'Las relaciones saludables se construyen sobre el respeto, la confianza, la comunicación y el reconocimiento mutuo. Mantener vínculos libres de violencia favorece tu bienestar emocional.',
                'recomendaciones' => [
                    'Continúa fortaleciendo relaciones basadas en el respeto y la empatía.',
                    'Mantén hábitos de autocuidado que favorezcan tu bienestar emocional.',
                    'Conserva y fortalece tus redes de apoyo.',
                    'Infórmate sobre relaciones saludables y estrategias de prevención.',
                ],
            ],
            'medio' => [
                'etiqueta' => 'Riesgo medio',
                'color'    => 'amarillo',
                'resultado' => 'Se identifican algunas conductas que podrían estar afectando tu bienestar emocional y la calidad de tus relaciones.',
                'reflexiona' => 'Algunas situaciones de control, descalificación, manipulación o intimidación suelen normalizarse con el tiempo; sin embargo, ninguna forma de violencia debe considerarse parte natural de una relación.',
                'recomendaciones' => [
                    'Reflexiona sobre cómo estas situaciones están impactando tu bienestar.',
                    'Habla con una persona de confianza sobre lo que estás viviendo.',
                    'Fortalece tus estrategias de autocuidado y establece límites saludables.',
                    'Si consideras que la situación persiste o aumenta, busca orientación profesional.',
                ],
            ],
            'alto' => [
                'etiqueta' => 'Riesgo alto',
                'color'    => 'naranja',
                'resultado' => 'Se identifican situaciones compatibles con violencia que podrían representar un riesgo importante para tu bienestar emocional, psicológico o físico.',
                'reflexiona' => 'Ninguna persona merece experimentar control, amenazas, humillaciones, agresiones o cualquier otra forma de violencia. No tienes que enfrentar estas situaciones en soledad.',
                'recomendaciones' => [
                    'Busca apoyo en una persona de confianza.',
                    'Evita aislarte y fortalece tu red de apoyo.',
                    'Considera solicitar orientación profesional.',
                    'Consulta las rutas institucionales y municipales de atención disponibles.',
                ],
            ],
            'critico' => [
                'etiqueta' => 'Riesgo crítico',
                'color'    => 'rojo',
                'resultado' => 'Se identifican situaciones que podrían representar un riesgo grave para tu integridad física, emocional o psicológica y que requieren atención prioritaria.',
                'reflexiona' => 'Las situaciones identificadas no deben minimizarse ni normalizarse. Tu bienestar y tu seguridad son importantes, y buscar ayuda es una decisión de protección y cuidado personal.',
                'recomendaciones' => [
                    'Busca ayuda de manera inmediata.',
                    'Contacta a una persona de confianza que pueda brindarte apoyo.',
                    'Activa las rutas institucionales y municipales de atención disponibles.',
                    'Si existe un riesgo inminente, comunícate con los servicios de emergencia.',
                ],
            ],
        ];

        ResultadoTamizaje::create([
         'modulo'    => 'violentometro',
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