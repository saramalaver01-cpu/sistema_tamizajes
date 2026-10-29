@extends('admin.app')

@section('title', 'Niveles | ' . $modulo->nombre)
@section('page-title', 'Niveles de resultado — ' . $modulo->nombre)

@section('content')

@if(session('success'))
<div class="admin-alert admin-alert-success">{{ session('success') }}</div>
@endif

<a href="{{ route('admin.modulos.index') }}" class="admin-btn" style="margin-bottom:1rem;">
    <i class="fa-solid fa-arrow-left"></i> Volver a módulos
</a>

<p style="margin-bottom:1rem; color:#64748b; font-size:0.85rem;">
    Los rangos son en <strong>porcentaje</strong> del puntaje posible (0% = todas las respuestas más bajas,
    100% = todas las más altas), así que siguen funcionando aunque agregues o quites preguntas después.
</p>

@foreach($modulo->niveles as $nivel)

<div class="admin-panel admin-panel-full" style="margin-bottom:1.25rem;">

    <form action="{{ route('admin.niveles.update', $nivel) }}" method="POST" style="display:flex; flex-direction:column; gap:0.6rem;">
        @csrf @method('PUT')

        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <input type="text" name="clave" class="admin-input" style="max-width:140px;" value="{{ $nivel->clave }}" placeholder="clave">
            <input type="text" name="etiqueta" class="admin-input" style="max-width:220px;" value="{{ $nivel->etiqueta }}" placeholder="Etiqueta visible">
            <select name="color" class="admin-input" style="max-width:140px;">
                <option value="verde" {{ $nivel->color=='verde'?'selected':'' }}>Verde</option>
                <option value="amarillo" {{ $nivel->color=='amarillo'?'selected':'' }}>Amarillo</option>
                <option value="naranja" {{ $nivel->color=='naranja'?'selected':'' }}>Naranja</option>
                <option value="rojo" {{ $nivel->color=='rojo'?'selected':'' }}>Rojo</option>
            </select>
            <input type="number" name="min_porcentaje" class="admin-input" style="max-width:100px;" value="{{ $nivel->min_porcentaje }}" placeholder="Min %">
            <input type="number" name="max_porcentaje" class="admin-input" style="max-width:100px;" value="{{ $nivel->max_porcentaje }}" placeholder="Max %">
        </div>

        <textarea name="resultado" class="admin-input" rows="2" placeholder="Texto de resultado">{{ $nivel->resultado }}</textarea>
        <textarea name="reflexiona" class="admin-input" rows="2" placeholder="Texto de reflexión">{{ $nivel->reflexiona }}</textarea>
        <textarea name="recomendaciones" class="admin-input" rows="4" placeholder="Recomendaciones (una por línea)">{{ implode("\n", $nivel->recomendaciones ?? []) }}</textarea>

        <div style="display:flex; gap:0.5rem;">
            <button type="submit" class="admin-btn admin-btn-primary">Guardar nivel</button>
        </div>
    </form>

    <form action="{{ route('admin.niveles.destroy', $nivel) }}" method="POST"
          onsubmit="return confirm('¿Eliminar este nivel?')" style="margin-top:0.5rem;">
        @csrf @method('DELETE')
        <button type="submit" class="admin-btn" style="color:#dc2626; border-color:#fecaca;">
            <i class="fa-solid fa-trash"></i> Eliminar este nivel
        </button>
    </form>

</div>

@endforeach

<div class="admin-panel admin-panel-full">
    <h3 style="margin-bottom:0.75rem;">Nuevo nivel</h3>
    <form action="{{ route('admin.niveles.store', $modulo) }}" method="POST" style="display:flex; flex-direction:column; gap:0.6rem;">
        @csrf
        <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
            <input type="text" name="clave" class="admin-input" style="max-width:140px;" placeholder="clave (ej: alto)" required>
            <input type="text" name="etiqueta" class="admin-input" style="max-width:220px;" placeholder="Etiqueta visible" required>
            <select name="color" class="admin-input" style="max-width:140px;">
                <option value="verde">Verde</option>
                <option value="amarillo">Amarillo</option>
                <option value="naranja">Naranja</option>
                <option value="rojo">Rojo</option>
            </select>
            <input type="number" name="min_porcentaje" class="admin-input" style="max-width:100px;" placeholder="Min %" required>
            <input type="number" name="max_porcentaje" class="admin-input" style="max-width:100px;" placeholder="Max %" required>
        </div>
        <textarea name="resultado" class="admin-input" rows="2" placeholder="Texto de resultado" required></textarea>
        <textarea name="reflexiona" class="admin-input" rows="2" placeholder="Texto de reflexión" required></textarea>
        <textarea name="recomendaciones" class="admin-input" rows="4" placeholder="Recomendaciones (una por línea)" required></textarea>
        <div>
            <button type="submit" class="admin-btn admin-btn-primary">
                <i class="fa-solid fa-plus"></i> Agregar nivel
            </button>
        </div>
    </form>
</div>

@endsection
