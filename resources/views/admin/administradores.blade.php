@extends('admin.app')

@section('title', 'Administradores | Panel Administrativo')
@section('page-title', 'Administradores')

@push('styles')
<style>
    .admin-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    @media (max-width: 640px) { .admin-form-grid { grid-template-columns: 1fr; } }

    .admin-form-campo { display: flex; flex-direction: column; gap: 6px; }
    .admin-form-campo label { font-size: .85rem; font-weight: 600; }
    .admin-form-campo input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d5d9e2;
        border-radius: 10px;
        background: #fff;
        color: #1f2933;
        font: inherit;
    }

    .admin-form-error { display: block; margin-top: 4px; color: #b42318; font-size: .82rem; }

    .admin-alerta-ok {
        margin-bottom: 20px;
        padding: 12px 16px;
        border-radius: 10px;
        background: #e8f8ee;
        color: #13693a;
        font-size: .9rem;
    }

    .admin-tabla-simple { width: 100%; border-collapse: collapse; font-size: .92rem; }
    .admin-tabla-simple th,
    .admin-tabla-simple td { text-align: left; padding: 10px 8px; border-bottom: 1px solid #eef0f4; }
    .admin-tabla-simple th { font-size: .78rem; text-transform: uppercase; opacity: .65; }

    .admin-badge-tu {
        margin-left: 8px;
        padding: 2px 8px;
        border-radius: 999px;
        background: #ede7fb;
        color: #5420a8;
        font-size: .72rem;
        font-weight: 600;
    }
</style>
@endpush

@section('content')

@if (session('success'))
    <div class="admin-alerta-ok">{{ session('success') }}</div>
@endif

<div class="admin-panels-grid">

    <!-- FORMULARIO -->

    <div class="admin-panel">

        <div class="admin-panel-header">
            <h3>Nuevo administrador</h3>
            <p>Esta persona podrá entrar al panel administrativo con su correo y contraseña.</p>
        </div>

        <form method="POST" action="{{ route('admin.administradores.store') }}">
            @csrf

            <div class="admin-form-grid" style="margin-bottom: 16px;">

                <div class="admin-form-campo">
                    <label for="name">Nombre completo</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-campo">
                    <label for="email">Correo institucional</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-campo">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" minlength="8" required>
                    @error('password')
                        <span class="admin-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="admin-form-campo">
                    <label for="password_confirmation">Confirmar contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" required>
                </div>

            </div>

            <button type="submit" class="dash-btn">Crear administrador</button>

        </form>

    </div>

    <!-- LISTADO -->

    <div class="admin-panel">

        <div class="admin-panel-header">
            <h3>Administradores actuales</h3>
            <p>{{ $administradores->count() }} en total.</p>
        </div>

        <div style="overflow-x: auto;">

            <table class="admin-tabla-simple">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($administradores as $admin)
                        <tr>
                            <td>
                                {{ $admin->name }}
                                @if ($admin->id === auth()->id())
                                    <span class="admin-badge-tu">Tú</span>
                                @endif
                            </td>
                            <td>{{ $admin->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    </div>

</div>

@endsection
