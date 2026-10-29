@extends('layouts.app')

@section('title', 'Bienestar Emocional | SBE')

@push('styles')
<link rel="stylesheet" href="{{ mix('css/Bienestar-emocional.css') }}">
@endpush

@section('content')

<section class="bienestar-page">
    <div class="bienestar-container">

        <!-- ENCABEZADO -->

        <div class="bienestar-header">
            <span class="bienestar-eyebrow">Tamizajes de bienestar</span>
            <h1>Bienestar Emocional</h1>
            <p>Un espacio para reflexionar sobre cómo te has sentido en las últimas dos semanas.</p>
        </div>

        <!-- BARRA DE PROGRESO -->

        <div class="bienestar-progress-bar">
            <div class="bienestar-progress-fill" id="bienestarProgressFill" style="width: 33%"></div>
        </div>

        <!-- ================= PASO 0: INTRODUCCIÓN ================= -->

        <div class="bienestar-step" id="stepIntro">

            <div class="bienestar-intro-grid">

                <div class="bienestar-intro-card">

                    <div class="bienestar-intro-warning">
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
                        relacionados con tu bienestar emocional.
                    </p>

                    <p>
                        Tus respuestas son completamente anónimas y confidenciales. No se solicitará
                        información que permita identificarte de manera directa.
                    </p>

                    <p>
                        Los resultados tienen un carácter orientador y educativo; no constituyen un
                        diagnóstico psicológico ni reemplazan la valoración realizada por un profesional.
                    </p>

                    <p>
                        Al finalizar recibirás una retroalimentación personalizada con recomendaciones
                        prácticas orientadas al autocuidado y al fortalecimiento de tu bienestar.
                    </p>

                    <div class="bienestar-intro-consent">
                        <label>
                            <input type="checkbox" id="consentIntroCheck">
                            He leído y comprendido las instrucciones anteriores.
                        </label>
                    </div>

                </div>

                <div class="bienestar-intro-safe-card">
                    <div class="bienestar-intro-safe-icon">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <h3>Cuidamos tu bienestar</h3>
                    <p>
                        Reconocer cómo te sientes es el primer paso para cuidarte. Esta herramienta
                        te acompaña a identificar fortalezas y áreas de atención.
                    </p>
                </div>

            </div>

            <div class="bienestar-intro-actions">

                <a href="{{ route('home') }}" class="bienestar-btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al menú
                </a>

                <button type="button" class="bienestar-btn-primary" id="btnContinuarIntro" disabled>
                    Continuar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 1: PREGUNTAS ================= -->

        <div class="bienestar-step" id="stepPreguntas" style="display:none;">

            <p class="bienestar-progress-text" id="bienestarProgressText">0 de 21 respondidas</p>

            <div id="dimensionesContainer">
                <!-- Las dimensiones y preguntas se generan por JS -->
            </div>

            <div class="bienestar-submit-bar">

                <button type="button" class="bienestar-btn-secondary" id="btnVolverIntro">
                    <i class="fa-solid fa-arrow-left"></i>
                    Anterior
                </button>

                <p id="bienestarWarning" class="bienestar-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Te faltan preguntas por responder
                </p>

                <button type="button" class="bienestar-btn-primary" id="btnEnviar">
                    Enviar respuestas
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 2: RESULTADO ================= -->

        <div class="bienestar-step" id="stepResultado" style="display:none;">

            <div class="bienestar-result-card" id="resultCard">

                <button type="button" class="bienestar-sound-toggle" id="btnSonido" title="Activar/silenciar sonido">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <!-- TERMÓMETRO ANIMADO -->

                <div class="bienestar-thermo-section">

                    <p class="bienestar-thermo-caption" id="thermoCaption">Procesando tus respuestas...</p>

                    <div class="bienestar-thermo-wrap">

                        <div class="bienestar-thermo-marks">
                            <span class="mark" data-mark="100">Requiere atención</span>
                            <span class="mark" data-mark="75">Seguimiento</span>
                            <span class="mark" data-mark="50">Estable</span>
                            <span class="mark" data-mark="25">Fortalecido</span>
                        </div>

                        <div class="bienestar-thermo">
                            <div class="bienestar-thermo-tube">
                                <div class="bienestar-thermo-mask" id="thermoMask"></div>
                            </div>
                            <div class="bienestar-thermo-bulb" id="thermoBulb">
                                <div class="bienestar-thermo-bulb-inner"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- DETALLE (aparece después de la animación) -->

                <div class="bienestar-result-details" id="resultDetails">

                    <div class="bienestar-result-badge" id="resultBadge">Bienestar estable</div>

                    <h2 id="resultTitulo">Resultado</h2>
                    <p id="resultTexto"></p>

                    <div class="bienestar-result-box">
                        <h4><i class="fa-solid fa-lightbulb"></i> Reflexiona</h4>
                        <p id="resultReflexiona"></p>
                    </div>

                    <div class="bienestar-result-box">
                        <h4><i class="fa-solid fa-list-check"></i> Recomendaciones</h4>
                        <ul id="resultRecomendaciones"></ul>
                    </div>

                    <div class="bienestar-resources" id="resultRecursos">

                        <h4>Recursos de acompañamiento</h4>

                        <div class="bienestar-resource-grid">

                            <a href="#" class="bienestar-resource-btn">
                                <i class="fa-solid fa-brain"></i>
                                Consejería psicológica
                            </a>

                            <a href="#" class="bienestar-resource-btn">
                                <i class="fa-solid fa-hands-holding-heart"></i>
                                Bienestar Universitario
                            </a>

                            <a href="{{ route('recursos') }}" class="bienestar-resource-btn">
                                <i class="fa-solid fa-book-open"></i>
                                Material psicoeducativo
                            </a>

                        </div>

                    </div>

                    <button type="button" class="bienestar-btn-secondary" id="btnRepetir">
                        Realizar otro ejercicio
                    </button>

                    <p class="bienestar-disclaimer">
                        Este resultado tiene un carácter orientador y educativo. No constituye un diagnóstico
                        psicológico ni reemplaza la valoración de un profesional de la salud.
                    </p>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- DATOS PARA JAVASCRIPT (ahora vienen armados desde BienestarEmocionalController@index) -->

<script>
    window.bienestarData = @json($bienestarData);
</script>

@endsection

@push('scripts')
<script src="{{ mix('js/Bienestar-emocional.js') }}" defer></script>
@endpush
