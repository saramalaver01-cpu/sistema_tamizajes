@extends('admin.app')

@section('title', 'Nuevo módulo | Panel Administrativo')
@section('page-title', 'Nuevo módulo')

@section('content')

<div class="admin-panel admin-panel-full">

    <form action="{{ route('admin.modulos.store') }}" method="POST">
        @csrf

        <div class="admin-form-group">
            <label>Clave interna (sin espacios, ej: nuevo_modulo)</label>
            <input type="text" name="clave" class="admin-input" value="{{ old('clave') }}" required>
            @error('clave') <small class="admin-error">{{ $message }}</small> @enderror
        </div>

        <div class="admin-form-group">
            <label>Nombre</label>
            <input type="text" name="nombre" class="admin-input" value="{{ old('nombre') }}" required>
            @error('nombre') <small class="admin-error">{{ $message }}</small> @enderror
        </div>

        <div class="admin-form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="admin-input" rows="3" required>{{ old('descripcion') }}</textarea>
            @error('descripcion') <small class="admin-error">{{ $message }}</small> @enderror
        </div>

        <div class="admin-form-group">
            <label>Ícono (clase de FontAwesome, ej: fa-heart)</label>
            <input type="text" name="icono" class="admin-input" value="{{ old('icono', 'fa-shield-heart') }}" required>
        </div>

        <div class="admin-form-group">
            <label>Color de la tarjeta</label>
            <select name="color" class="admin-input">
                <option value="green">Verde</option>
                <option value="purple">Morado</option>
                <option value="blue">Azul</option>
            </select>
        </div>

        <div class="admin-form-group">
            <label>Tiempo estimado (minutos)</label>
            <input type="number" name="tiempo_estimado" class="admin-input" value="{{ old('tiempo_estimado', 5) }}" required>
        </div>

        <div class="admin-form-group">
            <label>Orden</label>
            <input type="number" name="orden" class="admin-input" value="{{ old('orden', 0) }}">
        </div>

        <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
        <a href="{{ route('admin.modulos.index') }}" class="admin-btn">Cancelar</a>

    </form>

</div>

@endsection
