@extends('admin.app')

@section('title', 'Dashboard | Panel Administrativo')
@section('page-title', 'Dashboard general')

@section('content')

<!-- TARJETAS DE MÉTRICAS -->

<div class="admin-stats-grid">

    <div class="admin-stat-card">
        <div class="admin-stat-icon purple">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($totalAccesos) }}</span>
            <span class="admin-stat-label">Accesos de estudiantes (total)</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon green">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($accesosUltimos30) }}</span>
            <span class="admin-stat-label">Accesos en los últimos 30 días</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon blue">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <div>
            <span class="admin-stat-value">{{ number_format($totalTamizajes) }}</span>
            <span class="admin-stat-label">Tamizajes completados (total)</span>
        </div>
    </div>

</div>

<!-- PARTICIPANTES POR MÓDULO -->

<div class="admin-panels-grid">

    <div class="admin-panel">

        <div class="admin-panel-header">
            <h3>Participantes por módulo</h3>
            <p>Total de tamizajes completados en cada herramienta.</p>
        </div>

        <canvas id="chartModulos" height="220"></canvas>

    </div>

    <div class="admin-panel">

        <div class="admin-panel-header">
            <h3>Tendencia (últimos 14 días)</h3>
            <p>Tamizajes completados por día.</p>
        </div>

        <canvas id="chartTendencia" height="220"></canvas>

    </div>

</div>

<!-- DISTRIBUCIÓN DE NIVELES POR MÓDULO -->

<div class="admin-panel admin-panel-full">

    <div class="admin-panel-header">
        <h3>Distribución de niveles por módulo</h3>
        <p>Cantidad de resultados en cada nivel de clasificación.</p>
    </div>

    @if ($distribucionNiveles->isEmpty())

        <p class="admin-empty-state">
            <i class="fa-solid fa-inbox"></i>
            Aún no hay tamizajes completados para mostrar esta distribución.
        </p>

    @else

        <div class="admin-nivel-tables">

            @foreach ($distribucionNiveles as $moduloClave => $niveles)

                <div class="admin-nivel-table">

                    <h4>
                        {{ collect($modulos)->firstWhere('clave', $moduloClave)['nombre'] ?? $moduloClave }}
                    </h4>

                    <table>
                        <tbody>
                            @foreach ($niveles as $fila)
                                <tr>
                                    <td>{{ ucfirst(str_replace('_', ' ', $fila->nivel)) }}</td>
                                    <td class="admin-nivel-total">{{ $fila->total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            @endforeach

        </div>

    @endif

</div>

@push('scripts')
<script>

    const datosModulos = @json($modulos);
    const datosTendencia = @json($tendencia);

    document.addEventListener('DOMContentLoaded', () => {

        // GRÁFICO DE BARRAS — PARTICIPANTES POR MÓDULO

        new Chart(document.getElementById('chartModulos'), {
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

        // GRÁFICO DE LÍNEA — TENDENCIA

        new Chart(document.getElementById('chartTendencia'), {
            type: 'line',
            data: {
                labels: datosTendencia.map(d => d.fecha),
                datasets: [{
                    data: datosTendencia.map(d => d.total),
                    borderColor: '#5420a8',
                    backgroundColor: 'rgba(84, 32, 168, 0.08)',
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#5420a8',
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                },
            },
        });

    });

</script>
@endpush
@endsection