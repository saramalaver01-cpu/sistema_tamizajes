<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>{{ $d['titulo'] }}</title>
<style>
    @page { margin: 40px 40px 60px 40px; }

    body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1f2933; }

    h1 { font-size: 20px; margin: 0 0 6px; color: #5420a8; }
    h2 { font-size: 13px; margin: 24px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #5420a8; color: #1c2b6b; }
    h3 { font-size: 11px; margin: 14px 0 6px; }

    table.meta { border-collapse: collapse; }
    table.meta td { padding: 2px 10px 2px 0; vertical-align: top; }
    table.meta td.k { font-weight: bold; width: 105px; }

    table.tarjetas { width: 100%; border-collapse: separate; border-spacing: 8px 0; }
    td.tarjeta { border: 1px solid #e5e7eb; padding: 10px; text-align: center; width: 33%; }
    .valor { font-size: 20px; font-weight: bold; color: #5420a8; }
    .etiqueta { font-size: 9px; color: #555; }

    table.datos { width: 100%; border-collapse: collapse; }
    table.datos th { background: #5420a8; color: #ffffff; text-align: left; padding: 5px 6px; font-size: 9px; }
    table.datos td { padding: 4px 6px; border-bottom: 1px solid #e5e7eb; }
    .num { text-align: right; }

    .barra { background: #e8eaf0; height: 7px; }
    .relleno { height: 7px; }

    .nota { font-size: 8.5px; color: #555; margin-top: 6px; line-height: 1.4; }
    .vacio { color: #777; font-style: italic; padding: 6px 0; }
    .evitar { page-break-inside: avoid; }

    .pie { position: fixed; bottom: -38px; left: 0; right: 0; font-size: 8px; color: #777; text-align: center; }
    .pagina:before { content: counter(page); }
</style>
</head>
<body>

<div class="pie">
    {{ $d['titulo'] }} · Información agregada y anonimizada · Página <span class="pagina"></span>
</div>

<h1>{{ $d['titulo'] }}</h1>

<table class="meta">
    <tr><td class="k">Periodo</td><td>{{ $d['periodo'] }}</td></tr>
    <tr><td class="k">Filtros aplicados</td><td>{{ $d['filtros'] ?? 'Ninguno' }}</td></tr>
    <tr><td class="k">Generado el</td><td>{{ $d['generado_en'] }}</td></tr>
    <tr><td class="k">Generado por</td><td>{{ $d['generado_por'] }}</td></tr>
</table>

<h2>Resumen general</h2>

<table class="tarjetas">
    <tr>
        <td class="tarjeta">
            <div class="valor">{{ number_format($d['total']) }}</div>
            <div class="etiqueta">Tamizajes completados</div>
        </td>
        <td class="tarjeta">
            <div class="valor">{{ number_format($d['participantes']) }}</div>
            <div class="etiqueta">Participantes distintos</div>
        </td>
        <td class="tarjeta">
            <div class="valor">{{ number_format($d['sin_participante']) }}</div>
            <div class="etiqueta">Sin datos del participante</div>
        </td>
    </tr>
</table>

@if ($d['total'] === 0)

    <p class="vacio">No hay tamizajes en el periodo y con los filtros seleccionados.</p>

@else

    <h2>Resultados por instrumento</h2>

    <table class="datos">
        <tr>
            <th>Instrumento</th>
            <th class="num">Tamizajes</th>
            <th class="num">% del total</th>
            <th class="num">Promedio</th>
            <th class="num">Mínimo</th>
            <th class="num">Máximo</th>
            <th>Rango posible</th>
        </tr>
        @foreach ($d['modulos'] as $m)
            <tr>
                <td>{{ $m['nombre'] }}</td>
                <td class="num">{{ number_format($m['total']) }}</td>
                <td class="num">{{ $m['porcentaje'] }} %</td>
                <td class="num">{{ $m['promedio'] ?? '—' }}</td>
                <td class="num">{{ $m['minimo'] ?? '—' }}</td>
                <td class="num">{{ $m['maximo'] ?? '—' }}</td>
                <td>{{ $m['rango'] }}</td>
            </tr>
        @endforeach
    </table>

    <p class="nota">
        Un puntaje mayor indica más riesgo, malestar o carga. Los puntajes no son comparables entre instrumentos.
        En el Violentómetro el nivel también depende de las preguntas más graves, no solo del puntaje total.
        Los resultados son orientadores y no constituyen un diagnóstico psicológico ni médico.
    </p>

    <h2>Distribución por nivel</h2>

    @foreach ($d['distribucion'] as $m)

        <div class="evitar">
            <h3>{{ $m['nombre'] }} ({{ number_format($m['total']) }} tamizajes)</h3>

            @if ($m['total'] === 0)
                <p class="vacio">Sin tamizajes en el periodo.</p>
            @else
                <table class="datos">
                    <tr>
                        <th style="width: 34%;">Nivel</th>
                        <th class="num" style="width: 14%;">Tamizajes</th>
                        <th class="num" style="width: 12%;">%</th>
                        <th style="width: 40%;"></th>
                    </tr>
                    @foreach ($m['niveles'] as $n)
                        <tr>
                            <td>{{ $n['etiqueta'] }}</td>
                            <td class="num">{{ number_format($n['total']) }}</td>
                            <td class="num">{{ $n['porcentaje'] }} %</td>
                            <td>
                                <div class="barra">
                                    <div class="relleno" style="width: {{ $n['porcentaje'] }}%; background: {{ $n['color'] }};"></div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>

    @endforeach

    <h2>Tendencia por mes</h2>

    <table class="datos" style="width: 50%;">
        <tr>
            <th>Mes</th>
            <th class="num">Tamizajes</th>
        </tr>
        @foreach ($d['tendencia_mes'] as $f)
            <tr>
                <td>{{ $f['periodo'] }}</td>
                <td class="num">{{ number_format($f['total']) }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Características de quienes respondieron</h2>

    <p class="nota" style="margin-top: 0;">
        Solo incluye tamizajes con datos del participante ({{ number_format($d['total'] - $d['sin_participante']) }} de {{ number_format($d['total']) }}).
        @if ($d['min_grupo'] > 1)
            Los grupos con menos de {{ $d['min_grupo'] }} tamizajes se muestran como «&lt; {{ $d['min_grupo'] }}» para proteger el anonimato.
        @endif
    </p>

    @foreach ($d['desgloses'] as $bloque)

        <div class="evitar">
            <h3>{{ $bloque['titulo'] }}</h3>

            @if (! count($bloque['filas']))
                <p class="vacio">Sin datos.</p>
            @else
                <table class="datos" style="width: 70%;">
                    <tr>
                        <th>Categoría</th>
                        <th class="num">Tamizajes</th>
                        <th class="num">%</th>
                    </tr>
                    @foreach ($bloque['filas'] as $f)
                        <tr>
                            <td>{{ $f['categoria'] }}</td>
                            <td class="num">{{ $f['texto'] }}</td>
                            <td class="num">{{ $f['porcentaje'] !== null ? $f['porcentaje'].' %' : '—' }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>

    @endforeach

@endif

</body>
</html>
