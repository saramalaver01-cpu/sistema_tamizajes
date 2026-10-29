<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccesoEstudiante;
use App\Models\ResultadoTamizaje;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EstadisticasController extends Controller
{
    /*
     * Instrumentos, su color y sus niveles de clasificación (de menor a mayor gravedad).
     * Cada nivel se define como [etiqueta, color].
     */
    private const MODULOS = [
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

    // Campos sociodemográficos filtrables: clave del filtro => columna en 'participantes'
    private const CAMPOS_PARTICIPANTE = [
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

    private const DIAS_TENDENCIA_POR_DEFECTO = 30;
    private const MAX_DIAS_TENDENCIA = 90;

    public function index(Request $request)
    {
        $cfg = config('tamizaje.sociodemografico');

        /*
         * =====================================================
         * 1. VALIDACIÓN DE FILTROS
         * =====================================================
         * Si algún filtro no es válido no se aplica ninguno y se
         * muestra el motivo, en lugar de mostrar cifras engañosas.
         */

        $programasValidos = $request->query('facultad') && isset($cfg['facultades'][$request->query('facultad')])
            ? $cfg['facultades'][$request->query('facultad')]
            : collect($cfg['facultades'])->flatten()->all();

        $validator = Validator::make($request->query(), [
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

        $hayFiltroParticipante = count($filtrosParticipante) > 0;

        $aplicarFiltroParticipante = function ($query) use ($filtrosParticipante) {
            foreach ($filtrosParticipante as $clave => $valor) {
                if ($clave === 'edad_desde') {
                    $query->where('edad', '>=', $valor);
                } elseif ($clave === 'edad_hasta') {
                    $query->where('edad', '<=', $valor);
                } else {
                    $query->where(EstadisticasController::CAMPOS_PARTICIPANTE[$clave], $valor);
                }
            }
        };

        // Filtra cualquier consulta de resultados con todos los criterios activos
        $filtrar = function ($consulta) use ($modulo, $desde, $hasta, $hayFiltroParticipante, $aplicarFiltroParticipante) {
            return $consulta
                ->when($modulo, fn ($q) => $q->where('modulo', $modulo))
                ->when($desde, fn ($q) => $q->where('creado_en', '>=', $desde))
                ->when($hasta, fn ($q) => $q->where('creado_en', '<=', $hasta))
                ->when($hayFiltroParticipante, fn ($q) => $q->whereHas('participante', $aplicarFiltroParticipante));
        };

        // Resumen legible de los filtros activos, para mostrarlo arriba de los resultados
        $etiquetasParticipante = [
            'rol' => 'Rol', 'facultad' => 'Facultad', 'programa' => 'Programa',
            'semestre' => 'Semestre', 'jornada' => 'Jornada', 'sexo' => 'Sexo',
            'identidad_genero' => 'Identidad de género', 'orientacion_sexual' => 'Orientación sexual',
            'estado_civil' => 'Estado civil', 'nivel_educativo' => 'Nivel educativo',
            'estrato' => 'Estrato', 'municipio' => 'Municipio',
            'edad_desde' => 'Edad desde', 'edad_hasta' => 'Edad hasta',
        ];

        $partesResumen = [];
        if ($modulo) {
            $partesResumen[] = 'instrumento: '.self::MODULOS[$modulo]['nombre'];
        }
        if ($desde) {
            $partesResumen[] = 'desde '.$desde->format('d/m/Y');
        }
        if ($hasta) {
            $partesResumen[] = 'hasta '.$hasta->format('d/m/Y');
        }
        foreach ($filtrosParticipante as $clave => $valor) {
            $partesResumen[] = strtolower($etiquetasParticipante[$clave]).': '.$valor;
        }
        $resumenFiltros = $partesResumen ? implode(', ', $partesResumen) : null;

        /*
         * =====================================================
         * 2. INDICADORES
         * =====================================================
         */

        $totalTamizajes = $filtrar(ResultadoTamizaje::query())->count();

        $conteoPorModulo = $filtrar(ResultadoTamizaje::query())
            ->select('modulo', DB::raw('count(*) as total'))
            ->groupBy('modulo')
            ->pluck('total', 'modulo')
            ->all();

        $conteoNiveles = $filtrar(ResultadoTamizaje::query())
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

        /*
         * =====================================================
         * 3. TENDENCIA (un punto por día, incluidos los días en cero)
         * =====================================================
         */

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

        $serieRecortada = false;
        if ($inicioSerie->diffInDays($finSerie) > self::MAX_DIAS_TENDENCIA - 1) {
            $inicioSerie    = $finSerie->copy()->subDays(self::MAX_DIAS_TENDENCIA - 1);
            $serieRecortada = true;
        }

        $conteoDias = ResultadoTamizaje::query()
            ->when($modulo, fn ($q) => $q->where('modulo', $modulo))
            ->when($hayFiltroParticipante, fn ($q) => $q->whereHas('participante', $aplicarFiltroParticipante))
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

        /*
         * =====================================================
         * 4. ACCESOS (no se filtran por sociodemográficos: esa
         *    tabla no guarda datos del participante, solo el hash)
         * =====================================================
         */

        $estudiantesTotal = AccesoEstudiante::distinct()->count('email_hash');

        $estudiantesActivos30 = AccesoEstudiante::where('accedido_en', '>=', now()->subDays(30))
            ->distinct()
            ->count('email_hash');

        return view('admin.estadisticas', [
            'estudiantesTotal'      => $estudiantesTotal,
            'estudiantesActivos30'  => $estudiantesActivos30,
            'totalTamizajes'        => $totalTamizajes,
            'modulos'               => $modulos,
            'distribucion'          => $distribucion,
            'tendencia'             => $tendencia,
            'serie'                 => [
                'inicio'    => $inicioSerie->format('d/m/Y'),
                'fin'       => $finSerie->format('d/m/Y'),
                'recortada' => $serieRecortada,
            ],
            'opcionesModulo'        => collect(self::MODULOS)->map(fn ($m) => $m['nombre'])->all(),
            'opcionesSocio'         => $cfg,
            'valores'               => array_merge([
                'modulo' => null, 'desde' => null, 'hasta' => null,
                'rol' => null, 'facultad' => null, 'programa' => null, 'semestre' => null, 'jornada' => null,
                'sexo' => null, 'identidad_genero' => null, 'orientacion_sexual' => null,
                'estado_civil' => null, 'nivel_educativo' => null, 'estrato' => null, 'municipio' => null,
                'edad_desde' => null, 'edad_hasta' => null,
            ], $request->query()),
            'resumenFiltros'        => $resumenFiltros,
            'hayFiltroParticipante' => $hayFiltroParticipante,
            'erroresFiltro'         => $validator->errors()->all(),
        ]);
    }
}
