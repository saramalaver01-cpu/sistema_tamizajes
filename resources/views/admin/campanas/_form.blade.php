
<form action="{{ $action }}" method="POST" class="camp-form">
    @csrf

    @if(strtoupper($method ?? 'POST') !== 'POST')
    @method($method)
    @endif

    @php
    $fechaInicio = old(
    'fecha_inicio',
    $campana->fecha_inicio
    ? $campana->fecha_inicio->format('Y-m-d')
    : ''
    );

    $fechaFin = old(
    'fecha_fin',
    $campana->fecha_fin
    ? $campana->fecha_fin->format('Y-m-d')
    : ''
    );

    $modulosSeleccionados = old(
    'modulos',
    $seleccionados ?? []
    );

    $activa = old('activa', $campana->activa ?? true);
    @endphp

    {{-- INFORMACIÓN GENERAL --}}
    <section class="admin-panel">

        <div class="admin-panel-header">
            <h3>
                <i class="fa-solid fa-circle-info"></i>
                Información de la campaña
            </h3>
            <p>
                Define el nombre, la descripción y las
                fechas de aplicación.
            </p>
        </div>

        <div class="admin-form-group camp-wide">
            <label for="nombre">
                Nombre de la campaña *
            </label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                class="admin-input"
                maxlength="150"
                value="{{ old('nombre', $campana->nombre) }}"
                placeholder="Ej. Jornada de Bienestar Estudiantil"
                required
            >

            @error('nombre')
            <span class="admin-error">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="admin-form-group camp-wide">
            <label for="descripcion">
                Descripción
            </label>

            <textarea
                name="descripcion"
                id="descripcion"
                class="admin-input"
                rows="4"
                maxlength="500"
                placeholder="Describe el objetivo de la campaña..."
            >{{ old('descripcion', $campana->descripcion) }}</textarea>

            @error('descripcion')
            <span class="admin-error">
                    {{ $message }}
                </span>
            @enderror
        </div>

        <div class="camp-form-grid">

            <div class="admin-form-group">
                <label for="fecha_inicio">
                    Fecha de inicio *
                </label>

                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    class="admin-input"
                    value="{{ $fechaInicio }}"
                    required
                >

                @error('fecha_inicio')
                <span class="admin-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div class="admin-form-group">
                <label for="fecha_fin">
                    Fecha de finalización *
                </label>

                <input
                    type="date"
                    name="fecha_fin"
                    id="fecha_fin"
                    class="admin-input"
                    value="{{ $fechaFin }}"
                    required
                >

                @error('fecha_fin')
                <span class="admin-error">
                        {{ $message }}
                    </span>
                @enderror
            </div>

        </div>

    </section>

    {{-- SELECCIÓN DE MÓDULOS --}}
    <section class="admin-panel">

        <div class="admin-panel-header">
            <h3>
                <i class="fa-solid fa-layer-group"></i>
                Módulos de tamizaje
            </h3>

            <p>
                Selecciona los cuestionarios que estarán
                disponibles durante esta campaña.
            </p>
        </div>

        @if($errors->has('modulos') ||
        $errors->has('modulos.*'))
        <div class="admin-alert">
                <span class="admin-error">
                    {{ $errors->first('modulos') ?:
                       $errors->first('modulos.*') }}
                </span>
        </div>
        @endif

        <div class="camp-mod-grid">

            @forelse($modulos as $modulo)

            @php
            $gradiente = $modulo->gradiente ?? [];

            $colorInicio = $gradiente['from']
            ?? '#7c3aed';

            $colorFin = $gradiente['to']
            ?? '#5420a8';
            @endphp

            <label class="camp-mod-option">

                <input
                    type="checkbox"
                    name="modulos[]"
                    value="{{ $modulo->id }}"
                    @checked(in_array(
                    $modulo->id,
                $modulosSeleccionados
                ))
                >

                <span class="camp-mod-card">

                        <span
                            class="camp-mod-icon"
                            style="
                                --mod-from: {{ $colorInicio }};
                                --mod-to: {{ $colorFin }};
                            "
                        >
                            <i class="fa-solid {{ $modulo->icono ?? 'fa-clipboard-list' }}"></i>
                        </span>

                        <span class="camp-mod-info">
                            <strong>
                                {{ $modulo->nombre }}
                            </strong>

                            <small>
                                Incluir en esta campaña
                            </small>
                        </span>

                        <span class="camp-check">
                            <i class="fa-solid fa-check"></i>
                        </span>

                    </span>

            </label>

            @empty

            <div class="admin-empty-state">
                <i class="fa-solid fa-layer-group"></i>
                <p>
                    No hay módulos de tamizaje disponibles.
                </p>
            </div>

            @endforelse

        </div>

    </section>

    {{-- CONFIGURACIÓN --}}
    <section class="admin-panel">

        <div class="admin-panel-header">
            <h3>
                <i class="fa-solid fa-gear"></i>
                Configuración de la campaña
            </h3>

            <p>
                Define si la campaña estará habilitada.
            </p>
        </div>

        <input type="hidden" name="activa" value="0">

        <label class="camp-switch">

            <input
                type="checkbox"
                name="activa"
                value="1"
                @checked($activa)
            >

            <span class="camp-switch-slider"></span>

            <span class="camp-switch-text">

                <strong>
                    Campaña activa
                </strong>

                <small>
                    Permite el acceso durante las fechas
                    establecidas. Si la desactivas, la
                    campaña quedará pausada.
                </small>

            </span>

        </label>

    </section>

    {{-- ACCIONES --}}
    <div class="camp-form-actions">

        <button
            type="submit"
            class="admin-btn admin-btn-primary"
        >
            <i class="fa-solid fa-floppy-disk"></i>
            {{ $boton ?? 'Guardar campaña' }}
        </button>

        <a
            href="{{ route('admin.campanas.index') }}"
            class="admin-btn"
        >
            <i class="fa-solid fa-xmark"></i>
            Cancelar
        </a>

    </div>

</form>
