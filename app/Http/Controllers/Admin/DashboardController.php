<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccesoEstudiante;
use App\Models\ResultadoTamizaje;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAccesos = AccesoEstudiante::count();

        $accesosUltimos30 = AccesoEstudiante::where('accedido_en', '>=', now()->subDays(30))->count();

        $totalTamizajes = ResultadoTamizaje::count();

        $definicionModulos = [
            'violentometro'       => ['nombre' => 'Violentómetro', 'color' => '#198754'],
            'bienestar_emocional' => ['nombre' => 'Bienestar Emocional', 'color' => '#5420a8'],
            'carga_academica'     => ['nombre' => 'Carga Académica', 'color' => '#2563eb'],
        ];

        $conteoPorModulo = ResultadoTamizaje::select('modulo', DB::raw('count(*) as total'))
            ->groupBy('modulo')
            ->pluck('total', 'modulo');

        $modulos = collect($definicionModulos)->map(function ($info, $clave) use ($conteoPorModulo) {
            return [
                'clave'  => $clave,
                'nombre' => $info['nombre'],
                'color'  => $info['color'],
                'total'  => $conteoPorModulo[$clave] ?? 0,
            ];
        })->values();

        $distribucionNiveles = ResultadoTamizaje::select('modulo', 'nivel', DB::raw('count(*) as total'))
            ->groupBy('modulo', 'nivel')
            ->get()
            ->groupBy('modulo');

        $tendencia = ResultadoTamizaje::select(
                DB::raw('DATE(creado_en) as fecha'),
                DB::raw('count(*) as total')
            )
            ->where('creado_en', '>=', now()->subDays(13))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('admin.dashboard', compact(
            'totalAccesos',
            'accesosUltimos30',
            'totalTamizajes',
            'modulos',
            'distribucionNiveles',
            'tendencia'
        ));
    }
}