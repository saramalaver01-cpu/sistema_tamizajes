<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar sesión | SBE</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <link rel="stylesheet" href="{{ mix('css/login.css') }}">

</head>
<body>

    <!-- FONDO ANIMADO -->

    <div class="login-bg">
        <div class="login-blob blob-1"></div>
        <div class="login-blob blob-2"></div>
        <div class="login-blob blob-3"></div>
    </div>

    <div class="login-wrap">

        <div class="login-card">

            <div class="login-logo">
                <img src="{{ asset('images/logo-sibe.png') }}" alt="SBE">
            </div>

            <div class="login-heading">
                <h1>Bienvenido(a)</h1>
                <p>Selecciona cómo deseas ingresar a la plataforma.</p>
            </div>

            <!-- SELECTOR DE TIPO DE ACCESO -->

            <div class="login-tabs" id="loginTabs">

                <div class="login-tabs-indicator" id="tabsIndicator"></div>

                <button type="button" class="login-tab active" data-tab="estudiante">
                    <i class="fa-solid fa-user-graduate"></i>
                    Soy estudiante
                </button>

                <button type="button" class="login-tab" data-tab="administrador">
                    <i class="fa-solid fa-shield-halved"></i>
                    Administrador / Personal
                </button>

            </div>

            <!-- ===================== FORM ESTUDIANTE ===================== -->

            <div class="login-panel active" id="panelEstudiante">

                <p class="login-panel-note">
                    <i class="fa-solid fa-circle-info"></i>
                    Solo necesitas tu correo institucional. No se solicita contraseña ni datos que te identifiquen directamente.
                </p>

                @if ($errors->has('email_estudiante'))
                    <div class="login-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $errors->first('email_estudiante') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.estudiante') }}" class="login-form-estudiante">
                    @csrf

                    <div class="login-field">
                        <label for="email_estudiante">Correo institucional</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-envelope"></i>
                            <input
                                type="email"
                                id="email_estudiante"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="tunombre@uceva.edu.co"
                                required
                            >
                        </div>
                    </div>

                    <button type="submit" class="login-submit-btn">
                        <span>Continuar</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

            </div>

            <!-- ===================== FORM ADMINISTRADOR ===================== -->

            <div class="login-panel" id="panelAdministrador">

                <p class="login-panel-note">
                    <i class="fa-solid fa-lock"></i>
                    Acceso restringido para personal autorizado de la Vicerrectoría de Bienestar Universitario.
                </p>

                @if ($errors->has('email_admin'))
                    <div class="login-alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $errors->first('email_admin') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.administrador') }}" class="login-form-admin">
                    @csrf

                    <div class="login-field">
                        <label for="email_admin">Correo institucional</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-envelope"></i>
                            <input
                                type="email"
                                id="email_admin"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="admin@uceva.edu.co"
                                required
                            >
                        </div>
                    </div>

                    <div class="login-field">
                        <label for="password">Contraseña</label>
                        <div class="login-input-wrap">
                            <i class="fa-solid fa-key"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                            >
                            <button type="button" class="login-toggle-password" id="togglePassword" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="login-field-row">
                        <label class="login-remember">
                            <input type="checkbox" name="remember">
                            Recordarme
                        </label>

                        <a href="#" class="login-forgot">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="login-submit-btn">
                        <span>Iniciar sesión</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>

                </form>

            </div>

            <a href="{{ route('home') }}" class="login-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Volver al sitio principal
            </a>

        </div>

    </div>

    <script>
        window.loginActiveTab = @json(session('activeTab', 'estudiante'));
    </script>

    <script src="{{ mix('js/login.js') }}" defer></script>

</body>
</html>