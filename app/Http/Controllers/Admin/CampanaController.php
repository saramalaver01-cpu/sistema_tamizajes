<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campana;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampanaController extends Controller
{
    public function index()
    {
        $campanas = Campana::with('modulos')
            ->withCount('resultados')
            ->orderByDesc('fecha_inicio')
            ->get();

        $resumen = [
            'total'         => $campanas->count(),
            'en_curso'      => $campanas->where('estado', 'en_curso')->count(),
            'participantes' => $campanas->sum('resultados_count'),
        ];

        return view('admin.campanas.index', compact('campanas', 'resumen'));
    }

    public function create()
    {
        $campana = new Campana([
            'fecha_inicio' => today(),
            'fecha_fin'    => today()->addDays(30),
            'activa'       => true,
        ]);
        $modulos = Modulo::orderBy('orden')->get();
        $seleccionados = [];

        return view('admin.campanas.create', compact('campana', 'modulos', 'seleccionados'));
    }

    public function store(Request $request)
    {
        $data = $this->validar($request);

        $campana = Campana::create([
            'nombre'       => $data['nombre'],
            'descripcion'  => $data['descripcion'] ?? null,
            'codigo'       => Campana::generarCodigo(),
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin'],
            'activa'       => $request->boolean('activa'),
        ]);

        $campana->modulos()->sync($data['modulos']);

        return redirect()->route('admin.campanas.show', $campana)
            ->with('success', 'Campaña creada. Ya puedes compartir su enlace o su código QR.');
    }

    public function show(Campana $campana)
    {
        $campana->load('modulos')->loadCount('resultados');

        // Participantes por módulo (solo cantidades)
        $porModulo = $campana->resultados()
            ->select('modulo', DB::raw('COUNT(*) as total'))
            ->groupBy('modulo')
            ->pluck('total', 'modulo');

        // Distribución de niveles por módulo (solo cantidades)
        $niveles = $campana->resultados()
            ->select('modulo', 'nivel', DB::raw('COUNT(*) as total'))
            ->groupBy('modulo', 'nivel')
            ->get()
            ->groupBy('modulo');

        return view('admin.campanas.show', compact('campana', 'porModulo', 'niveles'));
    }

    public function edit(Campana $campana)
    {
        $modulos = Modulo::orderBy('orden')->get();
        $seleccionados = $campana->modulos()->pluck('modulos.id')->all();

        return view('admin.campanas.edit', compact('campana', 'modulos', 'seleccionados'));
    }

    public function update(Request $request, Campana $campana)
    {
        $data = $this->validar($request);

        $campana->update([
            'nombre'       => $data['nombre'],
            'descripcion'  => $data['descripcion'] ?? null,
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin'],
            'activa'       => $request->boolean('activa'),
        ]);

        $campana->modulos()->sync($data['modulos']);

        return redirect()->route('admin.campanas.show', $campana)
            ->with('success', 'Campaña actualizada correctamente.');
    }

    public function destroy(Campana $campana)
    {
        $campana->delete();

        return redirect()->route('admin.campanas.index')
            ->with('success', 'Campaña eliminada. Los resultados ya registrados se conservan.');
    }

    // Pausar o reactivar con un clic
    public function toggle(Campana $campana)
    {
        $campana->update(['activa' => ! $campana->activa]);

        return back()->with('success', $campana->activa
            ? "Campaña \"{$campana->nombre}\" reactivada."
            : "Campaña \"{$campana->nombre}\" pausada.");
    }

    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string|max:500',
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
            'modulos'      => 'required|array|min:1',
            'modulos.*'    => 'exists:modulos,id',
        ], [
            'nombre.required'          => 'Escribe un nombre para la campaña.',
            'fecha_inicio.required'    => 'Indica la fecha de inicio.',
            'fecha_fin.required'       => 'Indica la fecha de cierre.',
            'fecha_fin.after_or_equal' => 'La fecha de cierre no puede ser anterior a la de inicio.',
            'modulos.required'         => 'Selecciona al menos un módulo para la campaña.',
            'modulos.min'              => 'Selecciona al menos un módulo para la campaña.',
        ]);
    }
}
