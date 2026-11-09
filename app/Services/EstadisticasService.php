<?php

namespace App\Services;

use App\Models\ResultadoTamizaje;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class EstadisticasService
{

    public const MODULOS = [
        'violentometro' => [
            'nombre'  => 'Violentómetro',
            'color'   => '#198754',
            'niveles' => [
                'sin_riesgo' => ['Sin riesgo', '#198754'],
                'medio'      => ['Riesgo medio', '#f59e0b'],
                'alto'       => ['Riesgo alto', '#ea580c'],
                'critico'    => ['Riesgo crítico', '#dc2626'],
            ],
        ],
        'bienestar_emocional' => [
            'nombre'  => 'Bienestar emocional',
            'color'   => '#5420a8',
            'niveles' => [
                'fortalecido' => ['Bienestar fortalecido', '#198754'],
                'estable'     => ['Bienestar estable', '#2563eb'],
                'seguimiento' => ['Bienestar en seguimiento', '#f59e0b'],
                'atencion'    => ['Bienestar que requiere atención', '#ea580c'],
            ],
        ],
        'carga_academica' => [
            'nombre'  => 'Carga académica',
            'color'   => '#2563eb',
            'niveles' => [
                'baja'     => ['Carga baja', '#198754'],
                'moderada' => ['Carga moderada', '#f59e0b'],
                'alta'     => ['Carga alta', '#ea580c'],
                'critica'  => ['Carga crítica', '#dc2626'],
            ],
        ],
    ];


    public const CAMPOS_PARTICIPANTE = [
        'rol'                => 'rol_institucional',
        'facultad'           => 'facultad',
        'programa'           => 'programa_academico',
        'semestre'           => 'semestre',
        'jornada'            => 'jornada',
        'sexo'               => 'sexo',
        'identidad_genero'   => 'identidad_genero',
        'orientacion_sexual' => 'orientacion_sexual',
        'estado_civil'       => 'estado_civil',
        'nivel_educativo'    => 'nivel_educativo',
        'estrato'            => 'estrato',
        'municipio'          => 'municipio_residencia',
    ];

    private const ETIQUETAS = [
        'rol' => 'Rol', 'facultad' => 'Facultad', 'programa' => 'Programa',
        'semestre' => 'Semestre', 'jornada' => 'Jornada', 'sexo' => 'Sexo',
        'identidad_genero' => 'Identidad de género', 'orientacion_sexual' => 'Orientación sexual',
        'estado_civil' => 'Estado civil', 'nivel_educativo' => 'Nivel educativo',
        'estrato' => 'Estrato', 'municipio' => 'Municipio',
        'edad_desde' => 'Edad desde', 'edad_hasta' => 'Edad hasta',
    ];

    private const DIAS_TENDENCIA_POR_DEFECTO = 30;
    private const MAX_DIAS_TENDENCIA = 90;


    public function contexto(array $query): array
    {
        $cfg = config('tamizaje.sociodemografico');

        $facultad = $query['facultad'] ?? null;

        $programasValidos = is_string($facultad) && isset($cfg['facultades'][$facultad])
            ? $cfg['facultades'][$facultad]
            : collect($cfg['facultades'])->flatten()->all();

        $validator = Validator::make($query, [
            'modulo' => ['nullable', Rule::in(array_keys(self::MODULOS))],
            'desde'  => ['nullable', 'date_format:Y-m-d'],
            'hasta'  => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],

            'rol'      => ['nullable', Rule::in($cfg['roles'])],
            'facultad' => ['nullable', Rule::in(array_keys($cfg['facultades']))],
            'programa' => ['nullable', Rule::in($programasValidos)],
            'semestre' => ['nullable', 'integer', 'between:1,12'],
            'jornada'  => ['nullable', Rule::in($cfg['jornadas'])],

            'sexo'               => ['nullable', Rule::in($cfg['sexo'])],
            'identidad_genero'   => ['nullable', Rule::in($cfg['identidad_genero'])],
            'orientacion_sexual' => ['nullable', Rule::in($cfg['orientacion_sexual'])],
            'estado_civil'       => ['nullable', Rule::in($cfg['estado_civil'])],
            'nivel_educativo'    => ['nullable', Rule::in($cfg['nivel_educativo'])],
            'estrato'            => ['nullable', 'integer', 'between:1,6'],
            'municipio'          => ['nullable', 'string', 'max:100'],
            'edad_desde'         => ['nullable', 'integer', 'between:15,100'],
            'edad_hasta'         => ['nullable', 'integer', 'between:15,100', 'gte:edad_desde'],
        ], [
            'hasta.after_or_equal' => 'La fecha «Hasta» no puede ser anterior a «Desde».',
            'programa.in'          => 'El programa no corresponde a la facultad seleccionada.',
            'edad_hasta.gte'       => 'La edad «Hasta» no puede ser menor que «Desde».',
        ]);

        $filtros = $validator->fails() ? [] : $validator->validated();

        $modulo = $filtros['modulo'] ?? null;

        $desde = ! empty($filtros['desde'])
            ? Carbon::createFromFormat('Y-m-d', $filtros['desde'])->startOfDay()
            : null;

        $hasta = ! empty($filtros['hasta'])
            ? Carbon::createFromFormat('Y-m-d', $filtros['hasta'])->endOfDay()
            : null;

        // Filtros que aplican sobre la tabla 'participantes'
        $filtrosParticipante = collect(self::CAMPOS_PARTICIPANTE)
            ->keys()
            ->mapWithKeys(fn ($clave) => [$clave => $filtros[$clave] ?? null])
            ->filter()
            ->all();

        if (! empty($filtros['edad_desde'])) {
            $filtrosParticipante['edad_desde'] = $filtros['edad_desde'];
        }
        if (! empty($filtros['edad_hasta'])) {
            $filtrosParticipante['edad_hasta'] = $filtros['edad_hasta'];
        }

        // Resumen legible de los filtros activos
        $partes = [];
        if ($modulo) {
            $partes[] = 'instrumento: '.self::MODULOS[$modulo]['nombre'];
        }
        if ($desde) {
            $partes[] = 'desde '.$desde->format('d/m/Y');
        }
        if ($hasta) {
            $partes[] = 'hasta '.$hasta->format('d/m/Y');
        }
        foreach ($filtrosParticipante as $clave => $valor) {
            $partes[] = mb_strtolower(self::ETIQUETAS[$clave]).': '.$valor;
        }

        return [
            'cfg'                   => $cfg,
            'errores'               => $validator->errors()->all(),
            'filtros'               => $filtros,
            'modulo'                => $modulo,
            'desde'                 => $desde,
            'hasta'                 => $hasta,
            'filtrosParticipante'   => $filtrosParticipante,
            'hayFiltroParticipante' => count($filtrosParticipante) > 0,
            'resumen'               => $partes ? implode(', ', $partes) : null,
        ];
    }


    public function consulta(array $ctx)
    {
        $fp = $ctx['filtrosParticipante'];

        return ResultadoTamizaje::query()
            ->when($ctx['modulo'], fn ($q, $v) => $q->where('modulo', $v))
            ->when($ctx['desde'], fn ($q, $v) => $q->where('creado_en', '>=', $v))
            ->when($ctx['hasta'], fn ($q, $v) => $q->where('creado_en', '<=', $v))
            ->when($fp, fn ($q) => $q->whereHas('participante', function ($p) use ($fp) {
                foreach ($fp as $clave => $valor) {
                    if ($clave === 'edad_desde') {
                        $p->where('edad', '>=', $valor);
                    } elseif ($clave === 'edad_hasta') {
                        $p->where('edad', '<=', $valor);
                    } else {
                        $p->where(self::CAMPOS_PARTICIPANTE[$clave], $valor);
                    }
                }
            }));
    }


    public function indicadores(array $ctx): array
    {
        $modulo = $ctx['modulo'];

        $totalTamizajes = $this->consulta($ctx)->count();

        $conteoPorModulo = $this->consulta($ctx)
            ->select('modulo', DB::raw('count(*) as total'))
            ->groupBy('modulo')
            ->pluck('total', 'modulo')
            ->all();

        $conteoNiveles = $this->consulta($ctx)
            ->select('modulo', 'nivel', DB::raw('count(*) as total'))
            ->groupBy('modulo', 'nivel')
            ->get()
            ->groupBy('modulo');


        $modulos = collect(self::MODULOS)->map(fn ($info, $clave) => [
            'clave'  => $clave,
            'nombre' => $info['nombre'],
            'color'  => $info['color'],
            'total'  => (int) ($conteoPorModulo[$clave] ?? 0),
        ])->values()->all();


        $distribucion = collect(self::MODULOS)
            ->filter(fn ($info, $clave) => ! $modulo || $modulo === $clave)
            ->map(function ($info, $clave) use ($conteoNiveles, $conteoPorModulo) {

                $totalModulo = (int) ($conteoPorModulo[$clave] ?? 0);
                $porNivel    = $conteoNiveles->get($clave, collect())->pluck('total', 'nivel')->all();

                $niveles = collect($info['niveles'])->map(function ($definicion, $nivel) use ($porNivel, $totalModulo) {

                    $cantidad = (int) ($porNivel[$nivel] ?? 0);

                    return [
                        'etiqueta'   => $definicion[0],
                        'color'      => $definicion[1],
                        'total'      => $cantidad,
                        'porcentaje' => $totalModulo > 0 ? round($cantidad * 100 / $totalModulo, 1) : 0,
                    ];

                })->values()->all();

                return ['nombre' => $info['nombre'], 'total' => $totalModulo, 'niveles' => $niveles];

            })->values()->all();

        return compact('totalTamizajes', 'conteoPorModulo', 'modulos', 'distribucion');
    }


    public function tendencia(array $ctx): array
    {
        $desde = $ctx['desde'];
        $hasta = $ctx['hasta'];

        $hoy = now()->startOfDay();

        $finSerie = $hasta ? $hasta->copy()->startOfDay() : $hoy->copy();
        if ($finSerie->gt($hoy)) {
            $finSerie = $hoy->copy();
        }

        $inicioSerie = $desde
            ? $desde->copy()->startOfDay()
            : $finSerie->copy()->subDays(self::DIAS_TENDENCIA_POR_DEFECTO - 1);
        if ($inicioSerie->gt($finSerie)) {
            $inicioSerie = $finSerie->copy();
        }

        $recortada = false;
        if ($inicioSerie->diffInDays($finSerie) > self::MAX_DIAS_TENDENCIA - 1) {
            $inicioSerie = $finSerie->copy()->subDays(self::MAX_DIAS_TENDENCIA - 1);
            $recortada   = true;
        }

        $conteoDias = $this->consulta($ctx)
            ->whereBetween('creado_en', [$inicioSerie, $finSerie->copy()->endOfDay()])
            ->select(DB::raw('DATE(creado_en) as fecha'), DB::raw('count(*) as total'))
            ->groupBy('fecha')
            ->pluck('total', 'fecha')
            ->all();

        $tendencia = [];
        foreach (CarbonPeriod::create($inicioSerie, $finSerie) as $dia) {
            $clave       = $dia->format('Y-m-d');
            $tendencia[] = ['fecha' => $clave, 'total' => (int) ($conteoDias[$clave] ?? 0)];
        }

        return [
            'tendencia' => $tendencia,
            'serie'     => [
                'inicio'    => $inicioSerie->format('d/m/Y'),
                'fin'       => $finSerie->format('d/m/Y'),
                'recortada' => $recortada,
            ],
        ];
    }
}
