<!DOCTYPE html>

<html lang="es">


<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="Sistema de Bienestar Universitario"
    >

    <title>
        @yield('title', 'SBE | Sistema de Bienestar Universitario')
    </title>


    <!-- =====================================================
         FUENTES
    ====================================================== -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         ICONOS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


 <!-- =====================================================
     CSS DE LARAVEL MIX
====================================================== -->

<link rel="stylesheet" href="{{ mix('css/app.css') }}">

<script src="{{ mix('js/app.js') }}" defer></script>
@stack('styles')
</head>
@stack('scripts')
<body>


    <!-- =====================================================
         NAVBAR
    ====================================================== -->

    <header class="navbar">

        <div class="nav-container">


            <!-- LOGO -->

            <a
                href="{{ route('home') }}"
                class="logo-container"
            >

                <img
                    src="{{ asset('images/logo-sibe.png') }}"
                    alt="Sistema de Bienestar Universitario"
                    class="logo"
                >

            </a>


            <!-- NAVEGACIÓN -->

            <nav
                class="nav-links"
                id="navLinks"
            >

                <a href="{{ route('home') }}">
                    Inicio
                </a>

                <a href="#servicios">
                    Servicios
                </a>

                <a href="#modulos">
                    Herramientas
                </a>

                <a href="#violentometro">
                    Violentómetro
                </a>

            </nav>


            <!-- ACCIONES -->

            <div class="nav-actions">

    @if (session('estudiante_email'))

        <div class="user-menu" id="userMenu">

            <button type="button" class="user-menu-trigger" id="userMenuTrigger">
                <span class="user-avatar">
                    {{ strtoupper(substr(session('estudiante_email'), 0, 1)) }}
                </span>
                <span class="user-menu-label">
                    {{ Str::limit(session('estudiante_email'), 18) }}
                </span>
                <i class="fa-solid fa-chevron-down user-menu-caret"></i>
            </button>

            <div class="user-dropdown" id="userDropdown">

                <div class="user-dropdown-header">
                    <span class="user-avatar large">
                        {{ strtoupper(substr(session('estudiante_email'), 0, 1)) }}
                    </span>
                    <div>
                        <strong>Sesión de estudiante</strong>
                        <span>{{ session('estudiante_email') }}</span>
                    </div>
                </div>

                <a href="{{ route('home') }}#modulos" class="user-dropdown-item">
                    <i class="fa-solid fa-layer-group"></i>
                    Ver módulos
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="user-dropdown-item danger">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </div>

    @elseif (auth()->check() && auth()->user()->is_admin)

        <div class="user-menu" id="userMenu">

            <button type="button" class="user-menu-trigger" id="userMenuTrigger">
                <span class="user-avatar admin">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span class="user-menu-label">
                    {{ Str::limit(auth()->user()->name, 18) }}
                </span>
                <i class="fa-solid fa-chevron-down user-menu-caret"></i>
            </button>

            <div class="user-dropdown" id="userDropdown">

                <div class="user-dropdown-header">
                    <span class="user-avatar admin large">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div>
                        <strong>{{ auth()->user()->name }}</strong>
                        <span>Administrador</span>
                    </div>
                </div>

                <a href="{{ route('home') }}" class="user-dropdown-item">
                    <i class="fa-solid fa-gauge"></i>
                    Panel administrativo
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="user-dropdown-item danger">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Cerrar sesión
                    </button>
                </form>

            </div>

        </div>

    @else

        <a
            href="{{ route('login') }}"
            class="login-btn"
        >

            <i class="fa-solid fa-arrow-right-to-bracket"></i>

            Iniciar sesión

        </a>

    @endif


    <button
        type="button"
        class="menu-btn"
        id="menuBtn"
        aria-label="Abrir menú"
    >

        <i class="fa-solid fa-bars"></i>

    </button>

</div>

        </div>

    </header>


    <!-- =====================================================
         CONTENIDO
    ====================================================== -->

    @yield('content')


    <!-- =====================================================
         FOOTER
    ====================================================== -->

    <footer>

        <div class="footer-container">


            <div class="footer-brand">

                <img
                    src="{{ asset('images/logo-sibe.png') }}"
                    alt="SBE"
                >

                <p>
                    Sistema de Bienestar Universitario.
                    Un espacio de acompañamiento para
                    nuestra comunidad universitaria.
                </p>

            </div>


            <div class="footer-links">

                <div>

                    <h4>
                        Plataforma
                    </h4>

                    <a href="{{ route('home') }}">
                        Inicio
                    </a>

                    <a href="#servicios">
                        Servicios
                    </a>

                    <a href="#modulos">
                        Herramientas
                    </a>

                </div>


                <div>

                    <h4>
                        Bienestar
                    </h4>

                    <a href="#violentometro">
                        Violentómetro
                    </a>

                    <a href="#">
                        Contacto
                    </a>

                    <a href="#">
                        Ayuda
                    </a>

                </div>

            </div>

        </div>


        <div class="footer-bottom">

            <p>
                © {{ date('Y') }}
                Sistema de Bienestar Universitario.
                Todos los derechos reservados.
            </p>

        </div>

    </footer>


</body>

</html>