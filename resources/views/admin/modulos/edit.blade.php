@extends('admin.app')

@section('title', 'Editar módulo | Panel Administrativo')
@section('page-title', 'Editar módulo')

@section('content')

<div class="admin-panel admin-panel-full">

    <form action="{{ route('admin.modulos.update', $modulo) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="admin-form-group">
            <label>Clave interna</label>
            <input type="text" class="admin-input" value="{{ $modulo->clave }}" disabled>
            <small>La clave no se puede modificar porque está ligada al tamizaje.</small>
        </div>

        <div class="admin-form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="admin-input" value="{{ old('nombre', $modulo->nombre) }}" required>
        </div>

        <div class="admin-form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="admin-input" rows="3" required>{{ old('descripcion', $modulo->descripcion) }}</textarea>
        </div>

        <div class="admin-form-group">
            <label>Ícono (clase de FontAwesome)</label>
            <input type="text" name="icono" class="admin-input" value="{{ old('icono', $modulo->icono) }}" required>
        </div>

        <div class="admin-form-group">
            <label>Color de la tarjeta</label>
            <select name="color" class="admin-input">
                <option value="green" {{ $modulo->color == 'green' ? 'selected' : '' }}>Verde</option>
                <option value="purple" {{ $modulo->color == 'purple' ? 'selected' : '' }}>Morado</option>
                <option value="blue" {{ $modulo->color == 'blue' ? 'selected' : '' }}>Azul</option>
            </select>
        </div>

        <div class="admin-form-group">
            <label>Tiempo estimado (minutos)</label>
            <input type="number" name="tiempo_estimado" class="admin-input" value="{{ old('tiempo_estimado', $modulo->tiempo_estimado) }}" required>
        </div>

        <div class="admin-form-group">
            <label>Orden</label>
            <input type="number" name="orden" class="admin-input" value="{{ old('orden', $modulo->orden) }}">
        </div>

        <button type="submit" class="admin-btn admin-btn-primary">Actualizar</button>
        <a href="{{ route('admin.modulos.index') }}" class="admin-btn">Cancelar</a>

    </form>

</div>

@endsection
