
@extends('admin.app')

@section('title', 'Dashboard | Sistema de Tamizajes')
@section('page-title', 'Panel Administrativo')

@section('content')

<section class="sbe-welcome">

    <div class="sbe-welcome-decoration sbe-circle-one"></div>
    <div class="sbe-welcome-decoration sbe-circle-two"></div>

    <div class="sbe-welcome-content">

        <span class="sbe-welcome-badge">
            <i class="fa-solid fa-heart-pulse"></i>
            Bienestar Universitario · UCEVA
        </span>

        <h2>
            ¡Bienvenido al<br>
            <span>Sistema de Tamizajes!</span>
        </h2>

        <p>
            Te damos la bienvenida a la Plataforma Integral
            de Tamizaje y Autoconocimiento para el Bienestar
            Universitario.
        </p>

        <p class="sbe-welcome-description">
            Desde este espacio podrás administrar instrumentos
            de evaluación, gestionar campañas institucionales
            y consultar información que contribuye al bienestar
            de nuestra comunidad universitaria.
        </p>

        <div class="sbe-welcome-footer">
            <i class="fa-solid fa-graduation-cap"></i>
            <span>Unidad Central del Valle del Cauca</span>
        </div>

    </div>

    <div class="sbe-welcome-visual">

        <div class="sbe-logo-glow"></div>

        <div class="sbe-logo-frame">
            <img
                src="{{ asset('images/logo-sibe.png') }}"
                alt="Logo Bienestar Universitario UCEVA"
            >
        </div>

        <span class="sbe-visual-caption">
            Bienestar · Acompañamiento · Comunidad
        </span>

    </div>

</section>

@endsection
