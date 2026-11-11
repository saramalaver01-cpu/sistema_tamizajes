@extends('admin.app')

@section('title', 'Campañas | Panel Administrativo')
@section('page-title', 'Campañas')

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">{{ session('success') }}</div>
@endif

<!-- RESUMEN -->

<div class="admin-stats-grid">

    <div class="admin-stat-card">
        <div class="admin-stat-icon purple"><i class="fa-solid fa-qrcode"></i></div>
        <div>
            <span class="admin-stat-value">{{ $resumen['total'] }}</span>
            <span class="admin-stat-label">Campañas creadas</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon green"><i class="fa-solid fa-circle-play"></i></div>
        <div>
            <span class="admin-stat-value">{{ $resumen['en_curso'] }}</span>
            <span class="admin-stat-label">En curso ahora</span>
        </div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-icon blue"><i class="fa-solid fa-users"></i></div>
        <div>
            <span class="admin-stat-value">{{ number_format($resumen['participantes']) }}</span>
            <span class="admin-stat-label">Participantes en campañas</span>
        </div>
    </div>

</div>



<!-- LISTADO -->

<div class="camp-toolbar">
    <div>
        <h3>Tus campañas</h3>
        <p>Pausa, edita o consulta el código QR de cada una.</p>
    </div>
    <a href="{{ route('admin.campanas.create') }}" class="admin-btn admin-btn-primary">
        <i class="fa-solid fa-plus"></i> Nueva campaña
    </a>
</div>

@if($campanas->isEmpty())

<div class="camp-empty">
    <i class="fa-solid fa-qrcode"></i>
    <h4>Aún no hay campañas</h4>
    <p>Crea la primera para generar un enlace y un código QR de acceso.</p>
    <a href="{{ route('admin.campanas.create') }}" class="admin-btn admin-btn-primary">
        <i class="fa-solid fa-plus"></i> Crear campaña
    </a>
</div>

@else

<div class="camp-grid">

    @foreach($campanas as $campana)

    <article class="camp-card camp-{{ $campana->estado }}">

        <header class="camp-card-head">
            <h4>{{ $campana->nombre }}</h4>
            <span class="camp-badge camp-badge-{{ $campana->estado }}">{{ $campana->estado_etiqueta }}</span>
        </header>

        @if($campana->descripcion)
        <p class="camp-desc">{{ Str::limit($campana->descripcion, 110) }}</p>
        @endif

        <div class="camp-dates">
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

        <div class="camp-metrics">
            <div>
                <span class="camp-metric-value">{{ number_format($campana->resultados_count) }}</span>
                <span class="camp-metric-label">Participantes</span>
            </div>
            <div>
                <span class="camp-metric-value">{{ number_format($campana->ingresos) }}</span>
                <span class="camp-metric-label">Ingresos al enlace</span>
            </div>
        </div>

        <div class="camp-link-row">
            <input type="text" readonly value="{{ $campana->url_publica }}">
            <button type="button" class="admin-btn" data-copy="{{ $campana->url_publica }}" title="Copiar enlace">
                <i class="fa-regular fa-copy"></i> Copiar
            </button>
        </div>

        <footer class="camp-actions">
            <a href="{{ route('admin.campanas.show', $campana) }}" class="admin-btn admin-btn-primary">
                <i class="fa-solid fa-qrcode"></i> QR y detalle
            </a>

            <a href="{{ route('admin.campanas.edit', $campana) }}" class="admin-icon-btn" title="Editar">
                <i class="fa-solid fa-pen"></i>
            </a>

            <form action="{{ route('admin.campanas.toggle', $campana) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="admin-icon-btn" title="{{ $campana->activa ? 'Pausar' : 'Reactivar' }}">
                    <i class="fa-solid {{ $campana->activa ? 'fa-pause' : 'fa-play' }}"></i>
                </button>
            </form>

            <form action="{{ route('admin.campanas.destroy', $campana) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar esta campaña? Los resultados ya registrados se conservan, pero dejarán de asociarse a ella.')">
                @csrf @method('DELETE')
                <button type="submit" class="admin-icon-btn admin-icon-btn-danger" title="Eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </footer>

    </article>

    @endforeach

</div>

@endif

@endsection

@push('scripts')
<script>
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
