@extends('layouts.app')

@section('title', $modulo->nombre . ' | SBE')

@push('styles')
<link rel="stylesheet" href="{{ mix('css/tamizaje.css') }}">
@endpush

@section('content')

<section class="tmz-page" style="--tmz-from: {{ $modulo->gradiente['from'] }}; --tmz-to: {{ $modulo->gradiente['to'] }};">
    <div class="tmz-container">

        <!-- ENCABEZADO -->
        <div class="tmz-header">
            <span class="tmz-eyebrow">Tamizajes de bienestar</span>
            <h1>{{ $modulo->nombre }}</h1>
            <p>{{ $modulo->descripcion }}</p>
        </div>

        <!-- BARRA DE PROGRESO -->
        <div class="tmz-progress-bar">
            <div class="tmz-progress-fill" id="tmzProgressFill" style="width: 33%"></div>
        </div>

        <!-- ================= PASO 0: INTRODUCCIÓN ================= -->
        <div class="tmz-step" id="stepIntro">

            <div class="tmz-intro-grid">

                <div class="tmz-intro-card">

                    <div class="tmz-intro-warning">
                        <i class="fa-solid fa-circle-info"></i>
                        <div>
                            <strong>Información importante</strong>
                            <p>Por favor, lee las condiciones detenidamente antes de comenzar el tamizaje.</p>
                        </div>
                    </div>

                    <p>Bienvenido(a).</p>

                    <p>
                        La herramienta que estás a punto de utilizar corresponde a un ejercicio de tamizaje
                        y autoconocimiento diseñado para ayudarte a reflexionar sobre diferentes aspectos
                        de tu bienestar.
                    </p>

                    <p>
                        Tus respuestas son completamente anónimas y confidenciales. No se solicitará
                        información que permita identificarte de manera directa.
                    </p>

                    <p>
                        Los resultados tienen un carácter orientador y educativo; no constituyen un
                        diagnóstico psicológico ni una evaluación clínica.
                    </p>

                    <div class="tmz-intro-consent">
                        <label>
                            <input type="checkbox" id="consentIntroCheck">
                            He leído y comprendido las instrucciones anteriores.
                        </label>
                    </div>

                </div>

                <div class="tmz-intro-safe-card">
                    <div class="tmz-intro-safe-icon">
                        <i class="fa-solid {{ $modulo->icono }}"></i>
                    </div>
                    <h3>Cuidamos tu bienestar</h3>
                    <p>
                        Reconocer cómo te sientes es el primer paso para cuidarte. Esta herramienta
                        te acompaña a identificar fortalezas y áreas de atención.
                    </p>
                </div>

            </div>

            <div class="tmz-intro-actions">
                <a href="{{ route('home') }}" class="tmz-btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al menú
                </a>

                <button type="button" class="tmz-btn-primary" id="btnContinuarIntro" disabled>
                    Continuar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </div>

        <!-- ================= PASO 1: PREGUNTAS ================= -->
        <div class="tmz-step" id="stepPreguntas" style="display:none;">

            <p class="tmz-progress-text" id="tmzProgressText">0 respondidas</p>

            <div id="dimensionesContainer">
                <!-- Las dimensiones y preguntas se generan por JS -->
            </div>

            <div class="tmz-submit-bar">
                <button type="button" class="tmz-btn-secondary" id="btnVolverIntro">
                    <i class="fa-solid fa-arrow-left"></i>
                    Anterior
                </button>

                <p id="tmzWarning" class="tmz-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Te faltan preguntas por responder
                </p>

                <button type="button" class="tmz-btn-primary" id="btnEnviar">
                    Enviar respuestas
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>

        </div>

        <!-- ================= PASO 2: RESULTADO ================= -->
        <div class="tmz-step" id="stepResultado" style="display:none;">

            <div class="tmz-result-card" id="resultCard">

                <button type="button" class="tmz-sound-toggle" id="btnSonido" title="Activar/silenciar sonido">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <!-- TERMÓMETRO ANIMADO -->
                <div class="tmz-thermo-section">

                    <p class="tmz-thermo-caption" id="thermoCaption">Procesando tus respuestas...</p>

                    <div class="tmz-thermo-wrap">

                        <div class="tmz-thermo-marks" id="thermoMarks">
                            <!-- se generan por JS desde tamizajeData.niveles -->
                        </div>

                        <div class="tmz-thermo">
                            <div class="tmz-thermo-tube">
                                <div class="tmz-thermo-mask" id="thermoMask"></div>
                            </div>
                            <div class="tmz-thermo-bulb" id="thermoBulb">
                                <div class="tmz-thermo-bulb-inner"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- DETALLE (aparece después de la animación) -->
                <div class="tmz-result-details" id="resultDetails">

                    <div class="tmz-result-badge" id="resultBadge">Resultado</div>

                    <h2 id="resultTitulo">Resultado</h2>
                    <p id="resultTexto"></p>

                    <div class="tmz-result-box">
                        <h4><i class="fa-solid fa-lightbulb"></i> Reflexiona</h4>
                        <p id="resultReflexiona"></p>
                    </div>

                    <div class="tmz-result-box">
                        <h4><i class="fa-solid fa-list-check"></i> Recomendaciones</h4>
                        <ul id="resultRecomendaciones"></ul>
                    </div>

                    <div class="tmz-resources">
                        <h4>Recursos de acompañamiento</h4>
                        <div class="tmz-resource-grid">
                            <a href="#" class="tmz-resource-btn">
                                <i class="fa-solid fa-brain"></i>
                                Consejería psicológica
                            </a>
                            <a href="#" class="tmz-resource-btn">
                                <i class="fa-solid fa-hands-holding-heart"></i>
                                Bienestar Universitario
                            </a>
                            <a href="{{ route('recursos') }}" class="tmz-resource-btn">
                                <i class="fa-solid fa-book-open"></i>
                                Material psicoeducativo
                            </a>
                        </div>
                    </div>

                    <button type="button" class="tmz-btn-secondary" id="btnRepetir">
                        Realizar otro ejercicio
                    </button>

                    <p class="tmz-disclaimer">
                        Este resultado tiene un carácter orientador y educativo. No constituye un diagnóstico
                        psicológico ni reemplaza la valoración de un profesional de la salud.
                    </p>

                </div>

            </div>

        </div>

    </div>
</section>

<script>
    window.tamizajeData = @json($tamizajeData);
</script>

@endsection

@push('scripts')
<script src="{{ mix('js/tamizaje.js') }}" defer></script>
@endpush
