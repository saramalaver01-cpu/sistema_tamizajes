@extends('admin.app')

@section('title', $campana->nombre . ' | Campañas')
@section('page-title', 'Detalle de la campaña')

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">{{ session('success') }}</div>
@endif

<div class="camp-show-top">
    <a href="{{ route('admin.campanas.index') }}" class="admin-btn">
        <i class="fa-solid fa-arrow-left"></i> Volver a campañas
    </a>
    <a href="{{ route('admin.campanas.edit', $campana) }}" class="admin-btn">
        <i class="fa-solid fa-pen"></i> Editar
    </a>
</div>

<div class="camp-show-grid">

    <!-- QR Y ENLACE -->

    <section class="admin-panel camp-qr-panel">

        <div class="admin-panel-header">
            <h3>Código QR de acceso</h3>
            <p>Imprímelo en afiches o compártelo en pantalla. Al escanearlo, la persona entra directo a la campaña.</p>
        </div>

        <div class="camp-qr-box">
            <div id="qrCode"></div>
        </div>

        <div class="camp-link-row">
            <input type="text" readonly value="{{ $campana->url_publica }}">
            <button type="button" class="admin-btn" data-copy="{{ $campana->url_publica }}">
                <i class="fa-regular fa-copy"></i> Copiar
            </button>
        </div>

        <div class="camp-qr-actions">
            <button type="button" class="admin-btn admin-btn-primary" id="btnDescargarQR">
                <i class="fa-solid fa-download"></i> Descargar QR (PNG)
            </button>
            <a href="{{ $campana->url_publica }}" target="_blank" class="admin-btn">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Probar enlace
            </a>
        </div>

        <p class="camp-hint">
            <i class="fa-solid fa-circle-info"></i>
            El QR contiene la dirección desde la que estás viendo este panel. Descárgalo e imprímelo
            solo cuando la plataforma esté publicada en su dirección definitiva.
        </p>

    </section>

    <!-- INFORMACIÓN Y MÉTRICAS -->

    <section class="camp-info">

        <div class="admin-panel">
            <div class="camp-info-head">
                <div>
                    <h3>{{ $campana->nombre }}</h3>
                    <span class="camp-badge camp-badge-{{ $campana->estado }}">{{ $campana->estado_etiqueta }}</span>
                </div>
            </div>

            @if($campana->descripcion)
            <p class="camp-desc">{{ $campana->descripcion }}</p>
            @endif

            <div class="camp-dates camp-dates-big">
                <i class="fa-regular fa-calendar"></i>
                {{ $campana->fecha_inicio->format('d/m/Y') }}
                <i class="fa-solid fa-arrow-right"></i>
                {{ $campana->fecha_fin->format('d/m/Y') }}
            </div>

            <div class="camp-chips">
                @foreach($campana->modulos as $modulo)
                <span class="camp-chip" style="--chip-from: {{ $modulo->gradiente['from'] }}; --chip-to: {{ $modulo->gradiente['to'] }};">
                        <i class="fa-solid {{ $modulo->icono }}"></i> {{ $modulo->nombre }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="camp-stat-pair">
            <div class="admin-stat-card">
                <div class="admin-stat-icon blue"><i class="fa-solid fa-clipboard-check"></i></div>
                <div>
                    <span class="admin-stat-value">{{ number_format($campana->resultados_count) }}</span>
                    <span class="admin-stat-label">Participantes</span>
                </div>
            </div>
            <div class="admin-stat-card">
                <div class="admin-stat-icon purple"><i class="fa-solid fa-link"></i></div>
                <div>
                    <span class="admin-stat-value">{{ number_format($campana->ingresos) }}</span>
                    <span class="admin-stat-label">Ingresos al enlace</span>
                </div>
            </div>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-header">
                <h3>Participantes por módulo</h3>
                <p>Cantidad de tamizajes completados en esta campaña.</p>
            </div>

            @php $maximo = max(1, $porModulo->max() ?? 0); @endphp

            @foreach($campana->modulos as $modulo)
            @php $total = $porModulo[$modulo->clave] ?? 0; @endphp

            <div class="camp-bar-row">
                <div class="camp-bar-label">
                    <span>{{ $modulo->nombre }}</span>
                    <strong>{{ $total }}</strong>
                </div>
                <div class="camp-bar-track">
                    <div class="camp-bar-fill"
                         style="width: {{ ($total / $maximo) * 100 }}%; --bar-from: {{ $modulo->gradiente['from'] }}; --bar-to: {{ $modulo->gradiente['to'] }};"></div>
                </div>

                @if(isset($niveles[$modulo->clave]))
                <div class="camp-levels">
                    @foreach($niveles[$modulo->clave] as $fila)
                    <span class="camp-level">
                                    {{ ucfirst(str_replace('_', ' ', $fila->nivel)) }}: <strong>{{ $fila->total }}</strong>
                                </span>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach

            @if($campana->resultados_count === 0)
            <p class="camp-hint" style="margin-top: 14px;">
                <i class="fa-solid fa-circle-info"></i>
                Aún no hay participantes en esta campaña.
            </p>
            @endif
        </div>

        <div class="camp-privacy">
            <i class="fa-solid fa-shield-heart"></i>
            <p>
                <strong>Información agregada y anonimizada.</strong>
                Aquí solo ves cantidades. La plataforma no muestra respuestas individuales ni listados de personas.
            </p>
        </div>

    </section>

</div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const URL_CAMPANA = @json($campana->url_publica);
    const CODIGO_CAMPANA = @json($campana->codigo);

    // QR en pantalla
    new QRCode(document.getElementById('qrCode'), {
        text: URL_CAMPANA,
        width: 220,
        height: 220,
        colorDark: '#3b1578',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    // Descarga en alta resolución, con margen blanco para que escanee bien al imprimir
    document.getElementById('btnDescargarQR').addEventListener('click', function () {
        const tamano = 640;
        const margen = 48;

        const temporal = document.createElement('div');
        new QRCode(temporal, {
            text: URL_CAMPANA,
            width: tamano,
            height: tamano,
            colorDark: '#3b1578',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });

        const origen = temporal.querySelector('canvas');

        const lienzo = document.createElement('canvas');
        lienzo.width = tamano + margen * 2;
        lienzo.height = tamano + margen * 2;

        const ctx = lienzo.getContext('2d');
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, lienzo.width, lienzo.height);
        ctx.drawImage(origen, margen, margen);

        const enlace = document.createElement('a');
        enlace.href = lienzo.toDataURL('image/png');
        enlace.download = 'qr-campana-' + CODIGO_CAMPANA + '.png';
        enlace.click();
    });

    // Botón copiar
    document.querySelectorAll('[data-copy]').forEach(function (boton) {
        boton.addEventListener('click', async function () {
            const texto = boton.dataset.copy;

            try {
                await navigator.clipboard.writeText(texto);
            } catch (e) {
                const area = document.createElement('textarea');
                area.value = texto;
                document.body.appendChild(area);
                area.select();
                document.execCommand('copy');
                area.remove();
            }

            const original = boton.innerHTML;
            boton.innerHTML = '<i class="fa-solid fa-check"></i> ¡Copiado!';
            boton.classList.add('copied');

            setTimeout(function () {
                boton.innerHTML = original;
                boton.classList.remove('copied');
            }, 1800);
        });
    });
</script>
@endpush
