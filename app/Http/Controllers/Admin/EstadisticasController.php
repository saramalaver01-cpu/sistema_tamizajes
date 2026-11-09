<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccesoEstudiante;
use App\Services\EstadisticasService;
use Illuminate\Http\Request;

class EstadisticasController extends Controller
{
    public function index(Request $request, EstadisticasService $servicio)
    {
        $ctx       = $servicio->contexto($request->query());
        $ind       = $servicio->indicadores($ctx);
        $tendencia = $servicio->tendencia($ctx);


        $estudiantesTotal = AccesoEstudiante::distinct()->count('email_hash');

        $estudiantesActivos30 = AccesoEstudiante::where('accedido_en', '>=', now()->subDays(30))
            ->distinct()
            ->count('email_hash');

        return view('admin.estadisticas', [
            'estudiantesTotal'      => $estudiantesTotal,
            'estudiantesActivos30'  => $estudiantesActivos30,
            'totalTamizajes'        => $ind['totalTamizajes'],
            'modulos'               => $ind['modulos'],
            'distribucion'          => $ind['distribucion'],
            'tendencia'             => $tendencia['tendencia'],
            'serie'                 => $tendencia['serie'],
            'opcionesModulo'        => collect(EstadisticasService::MODULOS)->map(fn ($m) => $m['nombre'])->all(),
            'opcionesSocio'         => $ctx['cfg'],
            'valores'               => array_merge([
                'modulo' => null, 'desde' => null, 'hasta' => null,
                'rol' => null, 'facultad' => null, 'programa' => null, 'semestre' => null, 'jornada' => null,
                'sexo' => null, 'identidad_genero' => null, 'orientacion_sexual' => null,
                'estado_civil' => null, 'nivel_educativo' => null, 'estrato' => null, 'municipio' => null,
                'edad_desde' => null, 'edad_hasta' => null,
            ], $request->query()),
            'resumenFiltros'        => $ctx['resumen'],
            'hayFiltroParticipante' => $ctx['hayFiltroParticipante'],
            'erroresFiltro'         => $ctx['errores'],
        ]);
    }
}
