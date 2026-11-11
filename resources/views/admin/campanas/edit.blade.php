@extends('admin.app')

@section('title', 'Editar campaña | Panel Administrativo')
@section('page-title', 'Editar campaña')

@section('content')

    @include('admin.campanas._form', [
        'action' => route('admin.campanas.update', $campana),
        'method' => 'PUT',
        'boton'  => 'Guardar cambios',
    ])

@endsection
