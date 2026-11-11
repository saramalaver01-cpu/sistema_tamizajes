@extends('admin.app')

@section('title', 'Nueva campaña | Panel Administrativo')
@section('page-title', 'Nueva campaña')

@section('content')

    @include('admin.campanas._form', [
        'action' => route('admin.campanas.store'),
        'method' => 'POST',
        'boton'  => 'Crear campaña',
    ])

@endsection
