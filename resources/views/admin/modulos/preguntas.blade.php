@extends('admin.app')

@section('title', 'Preguntas | ' . $modulo->nombre)
@section('page-title', 'Preguntas — ' . $modulo->nombre)

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.modulos.index') }}" class="admin-btn" style="margin-bottom:1rem;">
    <i class="fa-solid fa-arrow-left"></i> Volver a módulos
</a>

@foreach($modulo->dimensiones as $dimension)

<div class="admin-panel admin-panel-full" style="margin-bottom:1.25rem;">

    <div style="display:flex; justify-content:space-between; align-items:flex-start;">

        <form action="{{ route('admin.dimensiones.update', $dimension) }}" method="POST" style="flex:1; display:flex; flex-direction:column; gap:0.5rem;">
            @csrf @method('PUT')
            <input type="text" name="titulo" class="admin-input" value="{{ $dimension->titulo }}" placeholder="Título de la dimensión">
            <textarea name="descripcion" class="admin-input" rows="2" placeholder="Descripción (opcional)">{{ $dimension->descripcion }}</textarea>
            <button type="submit" class="admin-btn" style="align-self:flex-start;">Guardar dimensión</button>
        </form>

        <form action="{{ route('admin.dimensiones.destroy', $dimension) }}" method="POST"
              onsubmit="return confirm('¿Eliminar esta dimensión y todas sus preguntas?')" style="margin-left:0.5rem;">
            @csrf @method('DELETE')
            <button type="submit" class="admin-icon-btn admin-icon-btn-danger" title="Eliminar dimensión">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>

    </div>

    <table class="admin-table" style="width:100%; margin-top:1rem;">
        <tbody>
        @foreach($dimension->preguntas as $pregunta)
        <tr>
        <tr>
            <td style="width:60%;">
                <form action="{{ route('admin.preguntas.update', $pregunta) }}" method="POST" style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap;">
                    @csrf @method('PUT')
                    <input type="text" name="texto" class="admin-input" value="{{ $pregunta->texto }}" style="flex:1; min-width:200px;">
                    <label style="display:flex; align-items:center; gap:0.3rem; font-size:0.8rem;">
                        <input type="checkbox" name="invertida" value="1" {{ $pregunta->invertida ? 'checked' : '' }}>
                        Invertida
                    </label>
                    <label style="display:flex; align-items:center; gap:0.3rem; font-size:0.8rem;">
                        <input type="checkbox" name="critica" value="1" {{ $pregunta->critica ? 'checked' : '' }}>
                        Crítica
                    </label>
                    <button type="submit" class="admin-btn">Guardar</button>
                </form>
            </td>
            <td style="width:10%; text-align:right;">
                <form action="{{ route('admin.preguntas.destroy', $pregunta) }}" method="POST"
                      onsubmit="return confirm('¿Eliminar esta pregunta?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="admin-icon-btn admin-icon-btn-danger" title="Eliminar">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </td>
        </tr>

        @endforeach

        <tr>
            <td colspan="2">
                <form action="{{ route('admin.preguntas.store', $dimension) }}" method="POST" style="display:flex; gap:0.5rem;">
                    @csrf
                    <input type="text" name="texto" class="admin-input" placeholder="Nueva pregunta...">
                    <button type="submit" class="admin-btn admin-btn-primary">
                        <i class="fa-solid fa-plus"></i> Agregar pregunta
                    </button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>

</div>

@endforeach

<!-- Agregar nueva dimensión -->

<div class="admin-panel admin-panel-full">
    <h3 style="margin-bottom:0.75rem;">Nueva dimensión</h3>
    <form action="{{ route('admin.dimensiones.store', $modulo) }}" method="POST" style="display:flex; flex-direction:column; gap:0.5rem; max-width:600px;">
        @csrf
        <input type="text" name="titulo" class="admin-input" placeholder="Ej: 7. Nueva categoría" required>
        <textarea name="descripcion" class="admin-input" rows="2" placeholder="Descripción (opcional)"></textarea>
        <button type="submit" class="admin-btn admin-btn-primary" style="align-self:flex-start;">
            <i class="fa-solid fa-plus"></i> Agregar dimensión
        </button>
    </form>
</div>

@endsection
