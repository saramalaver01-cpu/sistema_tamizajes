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
     * Las claves deben coincidir con las que guardan los controladores de cada módulo.
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

    private const DIAS_TENDENCIA_POR_DEFECTO = 30;
    private const MAX_DIAS_TENDENCIA = 90;

    public function index(Request $request)
    {
        /*
         * =====================================================
         * 1. FILTROS (instrumento y rango de fechas)
         * =====================================================
         * Si algún filtro no es válido no se aplica ninguno y se
         * muestra el motivo, en lugar de mostrar cifras engañosas.
         */

        $validator = Validator::make($request->query(), [
            'modulo' => ['nullable', Rule::in(array_keys(self::MODULOS))],
            'desde'  => ['nullable', 'date_format:Y-m-d'],
            'hasta'  => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
        ], [
            'modulo.in'            => 'Selecciona un instrumento válido.',
            'desde.date_format'    => 'La fecha «Desde» no es válida.',
            'hasta.date_format'    => 'La fecha «Hasta» no es válida.',
            'hasta.after_or_equal' => 'La fecha «Hasta» no puede ser anterior a «Desde».',
        ]);

        $filtros = $validator->fails() ? [] : $validator->validated();

        $modulo = $filtros['modulo'] ?? null;

        $desde = ! empty($filtros['desde'])
            ? Carbon::createFromFormat('Y-m-d', $filtros['desde'])->startOfDay()
            : null;

        $hasta = ! empty($filtros['hasta'])
            ? Carbon::createFromFormat('Y-m-d', $filtros['hasta'])->endOfDay()
            : null;

        // Aplica los filtros activos a cualquier consulta de resultados
        $filtrar = function ($consulta) use ($modulo, $desde, $hasta) {
            return $consulta
                ->when($modulo, fn ($q) => $q->where('modulo', $modulo))
                ->when($desde, fn ($q) => $q->where('creado_en', '>=', $desde))
                ->when($hasta, fn ($q) => $q->where('creado_en', '<=', $hasta));
        };

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
        $resumenFiltros = $partesResumen ? implode(', ', $partesResumen) : null;

        /*
         * =====================================================
         * 2. INDICADORES (todo sale de agrupaciones en la BD;
         *    un tamizaje = una fila, sin joins que la dupliquen)
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

        // Gráfico de barras: siempre los tres instrumentos (los que no aplican salen en cero)
        $modulos = collect(self::MODULOS)->map(fn ($info, $clave) => [
            'clave'  => $clave,
            'nombre' => $info['nombre'],
            'color'  => $info['color'],
            'total'  => (int) ($conteoPorModulo[$clave] ?? 0),
        ])->values()->all();

        // Distribución por nivel: todos los niveles de cada instrumento, incluso los que están en cero
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

                return [
                    'nombre'  => $info['nombre'],
                    'total'   => $totalModulo,
                    'niveles' => $niveles,
                ];

            })->values()->all();

        /*
         * =====================================================
         * 3. TENDENCIA (un punto por día, incluidos los días en cero)
         * =====================================================
         * Sin filtro de fechas: últimos 30 días.
         * Con filtro: el rango elegido (máximo 90 días).
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
         * 4. ACCESOS (estudiantes distintos, no inicios de sesión)
         * =====================================================
         */

        $estudiantesTotal = AccesoEstudiante::distinct()->count('email_hash');

        $estudiantesActivos30 = AccesoEstudiante::where('accedido_en', '>=', now()->subDays(30))
            ->distinct()
            ->count('email_hash');

        return view('admin.estadisticas', [
            'estudiantesTotal'     => $estudiantesTotal,
            'estudiantesActivos30' => $estudiantesActivos30,
            'totalTamizajes'       => $totalTamizajes,
            'modulos'              => $modulos,
            'distribucion'         => $distribucion,
            'tendencia'            => $tendencia,
            'serie'                => [
                'inicio'    => $inicioSerie->format('d/m/Y'),
                'fin'       => $finSerie->format('d/m/Y'),
                'recortada' => $serieRecortada,
            ],
            'opcionesModulo'       => collect(self::MODULOS)->map(fn ($m) => $m['nombre'])->all(),
            'valores'              => [
                'modulo' => $request->query('modulo'),
                'desde'  => $request->query('desde'),
                'hasta'  => $request->query('hasta'),
            ],
            'resumenFiltros'       => $resumenFiltros,
            'erroresFiltro'        => $validator->errors()->all(),
        ]);
    }
}
