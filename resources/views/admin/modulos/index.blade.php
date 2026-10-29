@extends('admin.app')

@section('title', 'Módulos | Panel Administrativo')

@section('page-title', 'Módulos')

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">
    {{ session('success') }}
</div>
@endif


{{-- ========================================================= --}}
{{-- VISTA PREVIA --}}
{{-- ========================================================= --}}

<div
    class="admin-panel admin-panel-full"
    style="margin-bottom: 1.5rem;"
>

    <div class="admin-panel-header">

        <h3>
            Vista previa
        </h3>

        <p>
            Así se ven actualmente los módulos para los estudiantes.
        </p>

    </div>


    <div class="modulos-preview-grid">

        @forelse($modulos as $modulo)

        <div
            class="modulo-preview-card
                {{ $modulo->activo ? '' : 'is-inactive' }}"
            style="
                    background:
                    linear-gradient(
                        135deg,
                        {{ $modulo->gradiente['from'] }},
                        {{ $modulo->gradiente['to'] }}
                    );
                "
        >

            <div>

                <div class="modulo-preview-icon">

                    <i class="fa-solid {{ $modulo->icono }}"></i>

                </div>


                <h4>
                    {{ $modulo->nombre }}
                </h4>


                <p>
                    {{ \Illuminate\Support\Str::limit(
                    $modulo->descripcion,
                    90
                    ) }}
                </p>

            </div>


            <div>

                <div class="modulo-preview-meta">

                        <span>

                            <i class="fa-regular fa-clock"></i>

                            ~{{ $modulo->tiempo_estimado }} min

                        </span>


                    <span>

                            <i class="fa-regular fa-square-check"></i>

                            {{ $modulo->total_preguntas }} preguntas

                        </span>

                </div>


                <span class="modulo-preview-status">

                        {{ $modulo->activo
                            ? 'Visible en el inicio'
                            : 'Oculto'
                        }}

                    </span>

            </div>

        </div>

        @empty

        <p>
            No hay módulos registrados.
        </p>

        @endforelse

    </div>

</div>



{{-- ========================================================= --}}
{{-- TABLA DE GESTIÓN --}}
{{-- ========================================================= --}}

<div class="admin-panel admin-panel-full">

    <div
        class="admin-panel-header"
        style="
            display: flex;
            justify-content: space-between;
            align-items: center;
        "
    >

        <div>

            <h3>
                Gestionar módulos
            </h3>

            <p>
                Activa, desactiva, edita y configura cada módulo.
            </p>

        </div>


        <a
            href="{{ route('admin.modulos.create') }}"
            class="admin-btn admin-btn-primary"
        >

            <i class="fa-solid fa-plus"></i>

            Nuevo módulo

        </a>

    </div>



    <table
        class="admin-table"
        style="
            width: 100%;
            margin-top: 1rem;
        "
    >

        <thead>

        <tr>

            <th>
                Orden
            </th>

            <th>
                Módulo
            </th>

            <th>
                Descripción
            </th>

            <th>
                Preguntas
            </th>

            <th>
                Estado
            </th>

            <th>
                Acciones
            </th>

        </tr>

        </thead>


        <tbody>

        @forelse($modulos as $modulo)

        <tr>

            <td>
                {{ $modulo->orden }}
            </td>


            <td>

                    <span
                        class="admin-icon-swatch"
                        style="
                            background:
                            linear-gradient(
                                135deg,
                                {{ $modulo->gradiente['from'] }},
                                {{ $modulo->gradiente['to'] }}
                            );
                        "
                    >

                        <i class="fa-solid {{ $modulo->icono }}"></i>

                    </span>

                &nbsp;

                {{ $modulo->nombre }}

            </td>


            <td>

                {{ \Illuminate\Support\Str::limit(
                $modulo->descripcion,
                55
                ) }}

            </td>


            <td>

                {{ $modulo->total_preguntas }}

            </td>


            <td>

                <form
                    action="{{ route(
                            'admin.modulos.toggle',
                            $modulo
                        ) }}"
                    method="POST"
                    style="display: inline;"
                >

                    @csrf

                    @method('PATCH')


                    <button
                        type="submit"
                        class="
                                admin-badge-btn
                                {{ $modulo->activo
                                    ? 'is-active'
                                    : 'is-inactive'
                                }}
                            "
                    >

                        {{ $modulo->activo
                        ? 'Activo'
                        : 'Inactivo'
                        }}

                    </button>

                </form>

            </td>


            <td>

                {{-- Preguntas --}}

                <a
                    href="{{ route(
                            'admin.modulos.preguntas.index',
                            $modulo
                        ) }}"
                    class="admin-icon-btn"
                    title="Ver preguntas"
                >

                    <i class="fa-solid fa-list-check"></i>

                </a>



                {{-- Editar --}}

                <a
                    href="{{ route(
                            'admin.modulos.edit',
                            $modulo
                        ) }}"
                    class="admin-icon-btn"
                    title="Editar"
                >

                    <i class="fa-solid fa-pen"></i>

                </a>



                {{-- Niveles del motor genérico --}}

                @if($modulo->motor_generico)

                <a
                    href="{{ route(
                                'admin.modulos.niveles.index',
                                $modulo
                            ) }}"
                    class="admin-icon-btn"
                    title="Niveles de resultado"
                >

                    <i class="fa-solid fa-layer-group"></i>

                </a>

                @endif



                {{-- Eliminar --}}

                <form
                    action="{{ route(
                            'admin.modulos.destroy',
                            $modulo
                        ) }}"
                    method="POST"
                    style="display: inline-block;"
                    onsubmit="
                            return confirm(
                                '¿Eliminar este módulo? Esta acción no se puede deshacer.'
                            )
                        "
                >

                    @csrf

                    @method('DELETE')


                    <button
                        type="submit"
                        class="
                                admin-icon-btn
                                admin-icon-btn-danger
                            "
                        title="Eliminar"
                    >

                        <i class="fa-solid fa-trash"></i>

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td
                colspan="6"
                class="admin-empty-state"
            >

                Aún no hay módulos creados.

            </td>

        </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection
