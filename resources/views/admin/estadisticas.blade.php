@extends('admin.app')

@section('title', 'Estadísticas | Panel Administrativo')
@section('page-title', 'Estadísticas de tamizajes')

@push('styles')
<style>
    /* Filtros */
    .dash-filtros { display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end; }
    .dash-campo { display: flex; flex-direction: column; gap: 6px; flex: 1 1 180px; min-width: 0; }
    .dash-campo label { font-size: .85rem; font-weight: 600; }
    .dash-campo select,
    .dash-campo input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d5d9e2;
        border-radius: 10px;
        background: #fff;
        color: #1f2933;
        font: inherit;
    }
    .dash-acciones { display: flex; gap: 14px; align-items: center; }
    .dash-btn {
        padding: 10px 18px;
        border: 0;
        border-radius: 10px;
        background: #5420a8;
        color: #fff;
        font: inherit;
        font-weight: 600;
        cursor: pointer;
    }
    .dash-btn-link { font-size: .9rem; font-weight: 600; color: #5420a8; text-decoration: none; }
    .dash-btn-link:hover { text-decoration: underline; }

    .dash-resumen { margin: 14px 0 0; font-size: .9rem; opacity: .85; }
    .dash-alerta {
        margin-top: 14px;
        padding: 10px 14px;
        border-radius: 10px;
        background: #fdecea;
        color: #9f1c12;
        font-size: .9rem;
    }
    .dash-alerta p { margin: 0; }

    /* Distribución por nivel */
    .dash-dist-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; }
    .dash-dist-card h4 { margin: 0 0 2px; font-size: 1rem; }
    .dash-dist-total { margin: 0 0 14px; font-size: .85rem; opacity: .75; }
    .dash-fila { margin-bottom: 14px; }
    .dash-fila-texto { display: flex; justify-content: space-between; gap: 12px; font-size: .9rem; margin-bottom: 5px; }
    .dash-fila-cifra { font-weight: 600; white-space: nowrap; }
    .dash-barra { height: 8px; border-radius: 999px; background: #e8eaf0; overflow: hidden; }
    .dash-barra span { display: block; height: 100%; border-radius: 999px; }

    @media (max-width: 640px) {
        .dash-acciones { width: 100%; }
        .dash-btn { flex: 1; }
    }
</style>
@endpush

@section('content')

<!-- FILTROS -->

<div class="admin-panel admin-panel-full" style="margin-bottom: 24px;">

    <div class="admin-panel-header">
        <h3>Filtros</h3>
        <p>Los indicadores y gráficos de tamizajes se recalculan con lo que elijas.</p>
    </div>

    <form method="GET" action="{{ route('admin.estadisticas') }}" class="dash-filtros">

        <div class="dash-campo">
            <label for="modulo">Instrumento</label>
            <select id="modulo" name="modulo">
                <option value="">Todos los instrumentos</option>
                @foreach ($opcionesModulo as $clave => $nombre)
                    <option value="{{ $clave }}" {{ $valores['modulo'] === $clave ? 'selected' : '' }}>{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="dash-campo">
            <label for="desde">Desde</label>
            <input type="date" id="desde" name="desde" value="{{ $valores['desde'] }}" max="{{ now()->toDateString() }}">
        </div>

        <div class="dash-campo">
            <label for="hasta">Hasta</label>
            <input type="date" id="hasta" name="hasta" value="{{ $valores['hasta'] }}" max="{{ now()->toDateString() }}">
        </div>

        <div class="dash-acciones">
            <button type="submit" class="dash-btn">Aplicar filtros</button>

            @if ($resumenFiltros || count($erroresFiltro))
                <a href="{{ route('admin.estadisticas') }}" class="dash-btn-link">Limpiar filtros</a>
            @endif
        </div>

    </form>

    @if (count($erroresFiltro))
        <div class="dash-alerta" role="alert">
            @foreach ($erroresFiltro as $mensaje)
                <p>{{ $mensaje }}</p>
            @endforeach
            <p>Mientras tanto se muestran los datos sin filtrar.</p>
        </div>
    @elseif ($resumenFiltros)
        <p class="dash-resumen">Filtros aplicados: {{ $resumenFiltros }}.</p>
    @endif

</div>

<!-- TARJETAS DE MÉTRICAS -->

<div class="admin-stats-grid">

    <div class="admin-stat-card">
        <div class="admin-stat-icon purple">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($estudiantesTotal) }}</span>
            <span class="admin-stat-label">Estudiantes que han ingresado</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon green">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($estudiantesActivos30) }}</span>
            <span class="admin-stat-label">Estudiantes activos en los últimos 30 días</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon blue">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($totalTamizajes) }}</span>
            <span class="admin-stat-label">
                {{ $resumenFiltros ? 'Tamizajes completados (con filtros)' : 'Tamizajes completados (total)' }}
            </span>
        </div>
    </div>

</div>

@if ($totalTamizajes === 0)

    <div class="admin-panel admin-panel-full">
        <p class="admin-empty-state">
            <i class="fa-solid fa-inbox"></i>
            {{ $resumenFiltros ? 'No hay tamizajes que coincidan con los filtros elegidos.' : 'Aún no hay tamizajes completados.' }}
        </p>
    </div>

@else

    <!-- GRÁFICOS -->

    <div class="admin-panels-grid">

        <div class="admin-panel">

            <div class="admin-panel-header">
                <h3>Participantes por módulo</h3>
                <p>Total de tamizajes completados en cada herramienta.</p>
            </div>

            <canvas id="chartModulos" height="220" role="img" aria-label="Gráfico de barras con los tamizajes por módulo"></canvas>

        </div>

        <div class="admin-panel">

            <div class="admin-panel-header">
                <h3>Tendencia diaria</h3>
                <p>
                    Del {{ $serie['inicio'] }} al {{ $serie['fin'] }}.
                    @if ($serie['recortada'])
                        Se muestran los últimos 90 días del rango elegido.
                    @endif
                </p>
            </div>

            <canvas id="chartTendencia" height="220" role="img" aria-label="Gráfico de líneas con los tamizajes por día"></canvas>

        </div>

    </div>

    <!-- DISTRIBUCIÓN DE NIVELES POR MÓDULO -->

    <div class="admin-panel admin-panel-full">

        <div class="admin-panel-header">
            <h3>Distribución de niveles por módulo</h3>
            <p>Cantidad y porcentaje de resultados en cada nivel de clasificación.</p>
        </div>

        <div class="dash-dist-grid">

            @foreach ($distribucion as $modulo)

                <div class="dash-dist-card">

                    <h4>{{ $modulo['nombre'] }}</h4>
                    <p class="dash-dist-total">{{ number_format($modulo['total']) }} tamizajes</p>

                    @if ($modulo['total'] === 0)

                        <p class="admin-empty-state">Sin tamizajes en este periodo.</p>

                    @else

                        @foreach ($modulo['niveles'] as $nivel)
                            <div class="dash-fila">
                                <div class="dash-fila-texto">
                                    <span>{{ $nivel['etiqueta'] }}</span>
                                    <span class="dash-fila-cifra">{{ number_format($nivel['total']) }} ({{ $nivel['porcentaje'] }} %)</span>
                                </div>
                                <div class="dash-barra" aria-hidden="true">
                                    <span style="width: {{ $nivel['porcentaje'] }}%; background: {{ $nivel['color'] }};"></span>
                                </div>
                            </div>
                        @endforeach

                    @endif

                </div>

            @endforeach

        </div>

    </div>

@endif

@push('scripts')
<script>

    const datosModulos = @json($modulos);
    const datosTendencia = @json($tendencia);

    document.addEventListener('DOMContentLoaded', () => {

        const lienzoModulos = document.getElementById('chartModulos');
        const lienzoTendencia = document.getElementById('chartTendencia');

        // GRÁFICO DE BARRAS — PARTICIPANTES POR MÓDULO

        if (lienzoModulos) {

            new Chart(lienzoModulos, {
                type: 'bar',
                data: {
                    labels: datosModulos.map(m => m.nombre),
                    datasets: [{
                        data: datosModulos.map(m => m.total),
                        backgroundColor: datosModulos.map(m => m.color),
                        borderRadius: 8,
                        maxBarThickness: 60,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                    },
                },
            });

        }

        // GRÁFICO DE LÍNEA — TENDENCIA DIARIA

        if (lienzoTendencia) {

            const formatoFecha = (iso) => new Date(iso + 'T00:00:00')
                .toLocaleDateString('es-CO', { day: '2-digit', month: 'short' });

            new Chart(lienzoTendencia, {
                type: 'line',
                data: {
                    labels: datosTendencia.map(d => formatoFecha(d.fecha)),
                    datasets: [{
                        data: datosTendencia.map(d => d.total),
                        borderColor: '#5420a8',
                        backgroundColor: 'rgba(84, 32, 168, 0.08)',
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#5420a8',
                        pointRadius: datosTendencia.length > 45 ? 0 : 3,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { ticks: { autoSkip: true, maxTicksLimit: 10 } },
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                    },
                },
            });

        }

    });

</script>
@endpush

@endsection
