<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Administrativo | SBE')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">

    @stack('styles')

</head>
<body>

    <div class="admin-layout">

        <!-- SIDEBAR -->

        <aside class="admin-sidebar" id="adminSidebar">

            <div class="admin-sidebar-logo">
                <img src="{{ asset('images/logo-sibe.png') }}" alt="SBE">
            </div>

            <nav class="admin-nav">

                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>

                <a href="#" class="admin-nav-link disabled">
                    <i class="fa-solid fa-layer-group"></i>
                    Módulos
                    <span class="admin-nav-soon">Próx.</span>
                </a>

                <a href="#" class="admin-nav-link disabled">
                    <i class="fa-solid fa-chart-column"></i>
                    Estadísticas
                    <span class="admin-nav-soon">Próx.</span>
                </a>

                <a href="#" class="admin-nav-link disabled">
                    <i class="fa-solid fa-qrcode"></i>
                    Campañas
                    <span class="admin-nav-soon">Próx.</span>
                </a>

                <a href="#" class="admin-nav-link disabled">
                    <i class="fa-solid fa-gear"></i>
                    Configuración
                    <span class="admin-nav-soon">Próx.</span>
                </a>

            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('home') }}" class="admin-nav-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al sitio
                </a>
            </div>

        </aside>

        <!-- CONTENIDO -->

        <div class="admin-content">

            <header class="admin-topbar">

                <button type="button" class="admin-sidebar-toggle" id="adminSidebarToggle">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <h1>@yield('page-title', 'Dashboard')</h1>

                <div class="admin-topbar-user">

                    <span class="user-avatar admin">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>

                    <span class="admin-topbar-name">{{ auth()->user()->name }}</span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="admin-logout-btn" title="Cerrar sesión">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>

                </div>

            </header>

            <main class="admin-main">
                @yield('content')
            </main>

        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <script src="{{ mix('js/admin.js') }}" defer></script>

    @stack('scripts')

</body>
</html>