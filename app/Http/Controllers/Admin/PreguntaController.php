<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\Dimension;
use App\Models\Pregunta;
use Illuminate\Http\Request;

class PreguntaController extends Controller
{
    // Listado de dimensiones y preguntas de un módulo
    public function index(Modulo $modulo)
    {
        $modulo->load('dimensiones.preguntas');
        return view('admin.modulos.preguntas', compact('modulo'));
    }

    // --- DIMENSIONES ---

    public function storeDimension(Request $request, Modulo $modulo)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $orden = $modulo->dimensiones()->max('orden') + 1;

        $modulo->dimensiones()->create([
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'] ?? null,
            'orden' => $orden,
        ]);

        return back()->with('success', 'Dimensión agregada.');
    }

    public function updateDimension(Request $request, Dimension $dimension)
    {
        $data = $request->validate([
            'titulo' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
        ]);

        $dimension->update($data);

        return back()->with('success', 'Dimensión actualizada.');
    }

    public function destroyDimension(Dimension $dimension)
    {
        $modulo = $dimension->modulo;
        $dimension->delete(); // las preguntas se borran en cascada
        $modulo->actualizarTotalPreguntas();

        return back()->with('success', 'Dimensión y sus preguntas eliminadas.');
    }

    // --- PREGUNTAS ---

    public function storePregunta(Request $request, Dimension $dimension)
    {
        $data = $request->validate([
            'texto' => 'required|string',
        ]);

        $orden = $dimension->preguntas()->max('orden') + 1;

        $dimension->preguntas()->create([
            'texto' => $data['texto'],
            'orden' => $orden,
        ]);

        $dimension->modulo->actualizarTotalPreguntas();

        return back()->with('success', 'Pregunta agregada.');
    }

    public function updatePregunta(Request $request, Pregunta $pregunta)
    {
        $data = $request->validate([
            'texto' => 'required|string',
        ]);

        $data['invertida'] = $request->boolean('invertida');
        $data['critica'] = $request->boolean('critica');

        $pregunta->update($data);

        return back()->with('success', 'Pregunta actualizada.');
    }

    public function destroyPregunta(Pregunta $pregunta)
    {
        $modulo = $pregunta->dimension->modulo;
        $pregunta->delete();
        $modulo->actualizarTotalPreguntas();

        return back()->with('success', 'Pregunta eliminada.');
    }
}
