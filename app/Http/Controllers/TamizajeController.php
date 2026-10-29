<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\ResultadoTamizaje;
use Illuminate\Http\Request;

class TamizajeController extends Controller
{
    public function index(string $clave)
    {
        $modulo = Modulo::with('dimensiones.preguntas', 'niveles')
            ->where('clave', $clave)
            ->where('motor_generico', true)
            ->where('activo', true)
            ->firstOrFail();

        $tamizajeData = [
            'csrf' => csrf_token(),
            'evaluarUrl' => route('tamizaje.evaluar', $modulo->clave),

            'dimensiones' => $modulo->dimensiones->map(fn ($dim) => [
                'titulo'      => $dim->titulo,
                'descripcion' => $dim->descripcion,
                'preguntas'   => $dim->preguntas->pluck('texto')->all(),
            ])->values()->all(),

            'opciones' => $modulo->opciones_escala ?? ['Nunca', 'Casi nunca', 'Algunas veces', 'Frecuentemente', 'Siempre'],

            // Marcas del termómetro (ordenadas de mayor a menor porcentaje, para pintarlas de arriba hacia abajo)
            'niveles' => $modulo->niveles->sortByDesc('min_porcentaje')->values()->map(fn ($n) => [
                'clave' => $n->clave,
                'etiqueta' => $n->etiqueta,
                'color' => $n->color,
                'min_porcentaje' => $n->min_porcentaje,
                'max_porcentaje' => $n->max_porcentaje,
            ])->all(),
        ];

        return view('layouts.tamizaje', compact('modulo', 'tamizajeData'));
    }

    public function evaluar(Request $request, string $clave)
    {
        $modulo = Modulo::with('dimensiones.preguntas', 'niveles')
            ->where('clave', $clave)
            ->where('motor_generico', true)
            ->firstOrFail();

        $preguntasFlat = $modulo->dimensiones->flatMap->preguntas->values();
        $totalPreguntas = $preguntasFlat->count();
        $maxEscala = count($modulo->opciones_escala ?? ['Nunca', 'Casi nunca', 'Algunas veces', 'Frecuentemente', 'Siempre']);

        $validated = $request->validate([
            'respuestas'   => ['required', 'array', 'size:' . $totalPreguntas],
            'respuestas.*' => ['required', 'integer', 'min:1', 'max:' . $maxEscala],
        ]);

        $respuestas = $validated['respuestas'];

        $puntaje = 0;
        $forzarNivelMaximo = false;

        foreach ($respuestas as $i => $valor) {
            $pregunta = $preguntasFlat[$i];

            $valorFinal = $pregunta->invertida ? ($maxEscala + 1 - $valor) : $valor;
            $puntaje += $valorFinal;

            if ($pregunta->critica && $valor == $maxEscala) {
                $forzarNivelMaximo = true;
            }
        }

        $minPosible = $totalPreguntas * 1;
        $maxPosible = $totalPreguntas * $maxEscala;
        $rango = max(1, $maxPosible - $minPosible);
        $porcentaje = (($puntaje - $minPosible) / $rango) * 100;

        $niveles = $modulo->niveles;

        if ($forzarNivelMaximo) {
            $nivel = $niveles->last();
        } else {
            $nivel = $niveles->first(function ($n) use ($porcentaje) {
                return $porcentaje >= $n->min_porcentaje && $porcentaje <= $n->max_porcentaje;
            }) ?? $niveles->last();
        }

        if (!$nivel) {
            abort(500, 'Este módulo no tiene niveles de resultado configurados.');
        }

        ResultadoTamizaje::create([
            'modulo'    => $modulo->clave,
            'nivel'     => $nivel->clave,
            'puntaje'   => $puntaje,
            'creado_en' => now(),
        ]);

        return response()->json([
            'nivel'   => $nivel->clave,
            'puntaje' => $puntaje,
            'porcentaje' => round($porcentaje),
            'etiqueta' => $nivel->etiqueta,
            'color' => $nivel->color,
            'resultado' => $nivel->resultado,
            'reflexiona' => $nivel->reflexiona,
            'recomendaciones' => $nivel->recomendaciones,
        ]);
    }
}
