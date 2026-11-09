<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;


class ReporteService
{
    private const DESGLOSES = [
        'rol_institucional'    => 'Rol institucional',
        'facultad'             => 'Facultad',
        'programa_academico'   => 'Programa académico',
        'semestre'             => 'Semestre',
        'jornada'              => 'Jornada',
        'sexo'                 => 'Sexo',
        'identidad_genero'     => 'Identidad de género',
        'orientacion_sexual'   => 'Orientación sexual',
        'estado_civil'         => 'Estado civil',
        'nivel_educativo'      => 'Nivel educativo',
        'estrato'              => 'Estrato socioeconómico',
        'municipio_residencia' => 'Municipio de residencia',
    ];


    private const ORDEN_NATURAL = ['semestre', 'estrato'];

    private const RANGOS = [
        'violentometro'       => '21 a 105',
        'bienestar_emocional' => '21 a 105',
        'carga_academica'     => '18 a 90',
    ];

    public function __construct(private EstadisticasService $estadisticas)
    {
    }

    public function datos(array $ctx, string $generadoPor): array
    {
        $min = (int) config('tamizaje.reporte_min_grupo', 5);

        $ind   = $this->estadisticas->indicadores($ctx);
        $total = $ind['totalTamizajes'];

        // Estadísticos del puntaje por instrumento
        $stats = $this->estadisticas->consulta($ctx)
            ->select(
                'modulo',
                DB::raw('avg(puntaje) as promedio'),
                DB::raw('min(puntaje) as minimo'),
                DB::raw('max(puntaje) as maximo')
            )
            ->groupBy('modulo')
            ->get()
            ->keyBy('modulo');

        $modulos = collect($ind['modulos'])->map(function ($m) use ($stats, $total) {
            $s = $stats->get($m['clave']);

            return [
                'clave'      => $m['clave'],
                'nombre'     => $m['nombre'],
                'total'      => $m['total'],
                'porcentaje' => $total > 0 ? round($m['total'] * 100 / $total, 1) : 0,
                'promedio'   => $s ? round((float) $s->promedio, 1) : null,
                'minimo'     => $s ? (int) $s->minimo : null,
                'maximo'     => $s ? (int) $s->maximo : null,
                'rango'      => self::RANGOS[$m['clave']] ?? '',
            ];
        })->all();

        $participantesUnicos = $this->estadisticas->consulta($ctx)
            ->whereNotNull('participante_id')
            ->distinct()
            ->count('participante_id');

        $sinParticipante = $this->estadisticas->consulta($ctx)
            ->whereNull('participante_id')
            ->count();


        $porMes = $this->estadisticas->consulta($ctx)
            ->select(DB::raw("DATE_FORMAT(creado_en, '%Y-%m') as periodo"), DB::raw('count(*) as total'))
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->map(fn ($f) => ['periodo' => $f->periodo, 'total' => (int) $f->total])
            ->all();

        $porDia = $this->estadisticas->consulta($ctx)
            ->select(DB::raw('DATE(creado_en) as periodo'), DB::raw('count(*) as total'))
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get()
            ->map(fn ($f) => ['periodo' => $f->periodo, 'total' => (int) $f->total])
            ->all();

        // Desglose sociodemográfico
        $desgloses = [];

        foreach (self::DESGLOSES as $columna => $titulo) {
            $desgloses[] = [
                'titulo' => $titulo,
                'filas'  => $this->desglose(
                    $ctx,
                    "participantes.$columna",
                    "participantes.$columna",
                    in_array($columna, self::ORDEN_NATURAL, true),
                    $min
                ),
            ];
        }

        // La edad se agrupa por rangos para no exponer edades individuales
        $rangosEdad = "CASE
            WHEN participantes.edad <= 17 THEN '15-17'
            WHEN participantes.edad <= 20 THEN '18-20'
            WHEN participantes.edad <= 23 THEN '21-23'
            WHEN participantes.edad <= 26 THEN '24-26'
            WHEN participantes.edad <= 30 THEN '27-30'
            ELSE '31 o más' END";

        array_splice($desgloses, 3, 0, [[
            'titulo' => 'Rango de edad',
            'filas'  => $this->desglose($ctx, $rangosEdad, 'participantes.edad', true, $min),
        ]]);

        return [
            'titulo'              => 'Reporte de tamizajes',
            'generado_en'         => now()->format('d/m/Y H:i'),
            'generado_por'        => $generadoPor,
            'periodo'             => $this->periodo($ctx),
            'filtros'             => $ctx['resumen'],
            'min_grupo'           => $min,
            'total'               => $total,
            'participantes'       => $participantesUnicos,
            'sin_participante'    => $sinParticipante,
            'modulos'             => $modulos,
            'distribucion'        => $ind['distribucion'],
            'tendencia_mes'       => $porMes,
            'tendencia_dia'       => $porDia,
            'desgloses'           => $desgloses,
        ];
    }

    private function periodo(array $ctx): string
    {
        $desde = $ctx['desde'];
        $hasta = $ctx['hasta'];

        if ($desde && $hasta) {
            return 'Del '.$desde->format('d/m/Y').' al '.$hasta->format('d/m/Y');
        }
        if ($desde) {
            return 'Desde el '.$desde->format('d/m/Y');
        }
        if ($hasta) {
            return 'Hasta el '.$hasta->format('d/m/Y');
        }

        return 'Todo el historial';
    }


    private function desglose(array $ctx, string $expresion, string $columna, bool $ordenNatural, int $min): array
    {
        $filas = $this->estadisticas->consulta($ctx)
            ->join('participantes', 'participantes.id', '=', 'resultados_tamizaje.participante_id')
            ->whereNotNull($columna)
            ->select(DB::raw("$expresion as categoria"), DB::raw('count(*) as total'))
            ->groupBy('categoria')
            ->when(
                $ordenNatural,
                fn ($q) => $q->orderBy('categoria'),
                fn ($q) => $q->orderByDesc('total')
            )
            ->get();

        $suma = max((int) $filas->sum('total'), 1);

        return $filas->map(function ($f) use ($min, $suma) {
            $t       = (int) $f->total;
            $oculto  = $min > 1 && $t < $min;

            return [
                'categoria'  => (string) $f->categoria,
                'total'      => $oculto ? null : $t,
                'texto'      => $oculto ? '< '.$min : (string) $t,
                'porcentaje' => $oculto ? null : round($t * 100 / $suma, 1),
            ];
        })->all();
    }
}
