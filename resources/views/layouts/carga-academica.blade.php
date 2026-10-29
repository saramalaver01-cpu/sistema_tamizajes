@extends('layouts.app')

@section('title', 'Carga Académica | SBE')

@push('styles')
<link rel="stylesheet" href="{{ mix('css/carga-academica.css') }}">
@endpush

@section('content')

<section class="carga-page">
    <div class="carga-container">

        <!-- ENCABEZADO -->

        <div class="carga-header">
            <span class="carga-eyebrow">Tamizajes de bienestar</span>
            <h1>Medidor de Carga Académica</h1>
            <p>Responde cada pregunta según tu experiencia en las últimas dos semanas.</p>
        </div>

        <!-- BARRA DE PROGRESO -->

        <div class="carga-progress-bar">
            <div class="carga-progress-fill" id="cargaProgressFill" style="width: 33%"></div>
        </div>

        <!-- ================= PASO 0: INTRODUCCIÓN ================= -->

        <div class="carga-step" id="stepIntro">

            <div class="carga-intro-grid">

                <div class="carga-intro-card">

                    <div class="carga-intro-warning">
                        <i class="fa-solid fa-circle-info"></i>
                        <div>
                            <strong>Información importante</strong>
                            <p>Por favor, lee las condiciones detenidamente antes de comenzar el tamizaje.</p>
                        </div>
                    </div>

                    <p>Bienvenido(a).</p>

                    <p>
                        La herramienta que estás a punto de utilizar corresponde a un ejercicio de tamizaje
                        y autoconocimiento diseñado para ayudarte a reflexionar sobre la forma en que estás
                        afrontando tus responsabilidades académicas.
                    </p>

                    <p>
                        Tus respuestas son completamente anónimas y confidenciales. No se solicitará
                        información que permita identificarte de manera directa.
                    </p>

                    <p>
                        Los resultados tienen un carácter orientador y educativo; no constituyen un
                        diagnóstico psicológico ni una evaluación clínica.
                    </p>

                    <p>
                        Al finalizar recibirás una retroalimentación personalizada con recomendaciones
                        orientadas a la organización académica, el autocuidado y el fortalecimiento de
                        estrategias de afrontamiento.
                    </p>

                    <div class="carga-intro-consent">
                        <label>
                            <input type="checkbox" id="consentIntroCheck">
                            He leído y comprendido las instrucciones anteriores.
                        </label>
                    </div>

                </div>

                <div class="carga-intro-safe-card">
                    <div class="carga-intro-safe-icon">
                        <i class="fa-solid fa-book-open-reader"></i>
                    </div>
                    <h3>Organiza tu bienestar</h3>
                    <p>
                        Reconocer cómo te afecta la carga académica es el primer paso para gestionarla
                        mejor y proteger tu bienestar mientras avanzas en tu formación.
                    </p>
                </div>

            </div>

            <div class="carga-intro-actions">

                <a href="{{ route('home') }}" class="carga-btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al menú
                </a>

                <button type="button" class="carga-btn-primary" id="btnContinuarIntro" disabled>
                    Continuar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 1: PREGUNTAS ================= -->

        <div class="carga-step" id="stepPreguntas" style="display:none;">

            <p class="carga-progress-text" id="cargaProgressText">0 de 18 respondidas</p>

            <div id="dimensionesContainer">
                <!-- Las dimensiones y preguntas se generan por JS -->
            </div>

            <div class="carga-submit-bar">

                <button type="button" class="carga-btn-secondary" id="btnVolverIntro">
                    <i class="fa-solid fa-arrow-left"></i>
                    Anterior
                </button>

                <p id="cargaWarning" class="carga-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Te faltan preguntas por responder
                </p>

                <button type="button" class="carga-btn-primary" id="btnEnviar">
                    Enviar respuestas
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 2: RESULTADO ================= -->

        <div class="carga-step" id="stepResultado" style="display:none;">

            <div class="carga-result-card" id="resultCard">

                <button type="button" class="carga-sound-toggle" id="btnSonido" title="Activar/silenciar sonido">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <!-- TERMÓMETRO ANIMADO -->

                <div class="carga-thermo-section">

                    <p class="carga-thermo-caption" id="thermoCaption">Procesando tus respuestas...</p>

                    <div class="carga-thermo-wrap">

                        <div class="carga-thermo-marks">
                            <span class="mark" data-mark="100">Crítica</span>
                            <span class="mark" data-mark="75">Alta</span>
                            <span class="mark" data-mark="50">Moderada</span>
                            <span class="mark" data-mark="25">Baja</span>
                        </div>

                        <div class="carga-thermo">
                            <div class="carga-thermo-tube">
                                <div class="carga-thermo-mask" id="thermoMask"></div>
                            </div>
                            <div class="carga-thermo-bulb" id="thermoBulb">
                                <div class="carga-thermo-bulb-inner"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- DETALLE (aparece después de la animación) -->

                <div class="carga-result-details" id="resultDetails">

                    <div class="carga-result-badge" id="resultBadge">Carga académica baja</div>

                    <h2 id="resultTitulo">Resultado</h2>
                    <p id="resultTexto"></p>

                    <div class="carga-result-box">
                        <h4><i class="fa-solid fa-lightbulb"></i> Reflexiona</h4>
                        <p id="resultReflexiona"></p>
                    </div>

                    <div class="carga-result-box">
                        <h4><i class="fa-solid fa-list-check"></i> Recomendaciones</h4>
                        <ul id="resultRecomendaciones"></ul>
                    </div>

                    <div class="carga-resources" id="resultRecursos">

                        <h4>Recursos de acompañamiento</h4>

                        <div class="carga-resource-grid">

                            <a href="#" class="carga-resource-btn">
                                <i class="fa-solid fa-user-graduate"></i>
                                Acompañamiento académico
                            </a>

                            <a href="#" class="carga-resource-btn">
                                <i class="fa-solid fa-brain"></i>
                                Consejería psicológica
                            </a>

                            <a href="{{ route('recursos') }}" class="carga-resource-btn">
                                <i class="fa-solid fa-book-open"></i>
                                Material psicoeducativo
                            </a>

                        </div>

                    </div>

                    <button type="button" class="carga-btn-secondary" id="btnRepetir">
                        Realizar otro ejercicio
                    </button>

                    <p class="carga-disclaimer">
                        Este resultado tiene un carácter orientador y educativo. No constituye un diagnóstico
                        psicológico ni una evaluación clínica.
                    </p>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- DATOS PARA JAVASCRIPT (ahora vienen armados desde CargaAcademicaController@index) -->

<script>
    window.cargaData = @json($cargaData);
</script>

@endsection

@push('scripts')
<script src="{{ mix('js/carga-academica.js') }}" defer></script>
@endpush
