@extends('layouts.app')

@section('title', 'Violentómetro | SBE')

@push('styles')
<link rel="stylesheet" href="{{ mix('css/violentometro.css') }}">
@endpush

@section('content')

<section class="violent-page">
    <div class="violent-container">

        <!-- ENCABEZADO -->

        <div class="violent-header">
            <span class="violent-eyebrow">Tamizajes de bienestar</span>
            <h1>Violentómetro Interactivo</h1>
            <p>Un espacio para reflexionar sobre tus relaciones y reconocer señales de alerta.</p>
        </div>

        <!-- BARRA DE PROGRESO -->

        <div class="violent-progress-bar">
            <div class="violent-progress-fill" id="violentProgressFill" style="width: 25%"></div>
        </div>

        <!-- ================= PASO 0: INTRODUCCIÓN ================= -->

        <div class="violent-step" id="stepIntro">

            <div class="violent-intro-grid">

                <div class="violent-intro-card">

                    <div class="violent-intro-warning">
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
                        relacionados con tu bienestar.
                    </p>

                    <p>
                        Tus respuestas son completamente anónimas y confidenciales. No se solicitará
                        información que permita identificarte de manera directa.
                    </p>

                    <p>
                        Los resultados tienen un carácter orientador y educativo; no constituyen un
                        diagnóstico psicológico o médico ni reemplazan la valoración realizada por un profesional.
                    </p>

                    <p>
                        Al finalizar recibirás una retroalimentación personalizada con recomendaciones
                        prácticas orientadas al autocuidado y al fortalecimiento de tu bienestar.
                    </p>

                    <div class="violent-intro-consent">
                        <label>
                            <input type="checkbox" id="consentIntroCheck">
                            He leído y comprendido las instrucciones anteriores.
                        </label>
                    </div>

                </div>

                <div class="violent-intro-safe-card">
                    <div class="violent-intro-safe-icon">
                        <i class="fa-solid fa-shield-heart"></i>
                    </div>
                    <h3>Entorno seguro</h3>
                    <p>
                        Tu bienestar es nuestra prioridad. Esta evaluación es el primer paso para
                        acompañarte a construir relaciones sanas y libres de violencia.
                    </p>
                </div>

            </div>

            <div class="violent-intro-actions">

                <a href="{{ route('home') }}" class="violent-btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>
                    Volver al menú
                </a>

                <button type="button" class="violent-btn-primary" id="btnContinuarIntro" disabled>
                    Continuar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 1: CONTEXTO ================= -->

        <div class="violent-step" id="stepContexto" style="display:none;">

            <div class="violent-card">

                <h2>¿Sobre qué tipo de relación deseas realizar este ejercicio?</h2>

                <p class="violent-subtitle">
                    Selecciona el contexto que quieres evaluar. Tus respuestas son anónimas y confidenciales.
                </p>

                <div class="violent-context-grid" id="contextoGrid">
                    <!-- Las opciones se generan por JS -->
                </div>

                <div class="violent-consent">
                    <label>
                        <input type="checkbox" id="consentCheck">
                        Declaro que he leído y comprendido que este ejercicio es anónimo, de carácter orientador
                        y educativo, y que no constituye un diagnóstico psicológico ni médico.
                    </label>
                </div>

                <button type="button" class="violent-btn-primary" id="btnIniciar" disabled>
                    Comenzar
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 2: PREGUNTAS ================= -->

        <div class="violent-step" id="stepPreguntas" style="display:none;">

            <p class="violent-progress-text" id="violentProgressText">0 de 21 respondidas</p>

            <div id="dimensionesContainer">
                <!-- Las dimensiones y preguntas se generan por JS -->
            </div>

            <div class="violent-submit-bar">

                <button type="button" class="violent-btn-secondary" id="btnVolverContexto">
                    <i class="fa-solid fa-arrow-left"></i>
                    Cambiar contexto
                </button>

                <p id="violentWarning" class="violent-warning">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    Te faltan preguntas por responder
                </p>

                <button type="button" class="violent-btn-primary" id="btnEnviar">
                    Ver mi resultado
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </div>

        </div>

        <!-- ================= PASO 3: RESULTADO ================= -->

        <div class="violent-step" id="stepResultado" style="display:none;">

            <div class="violent-result-card" id="resultCard">

                <button type="button" class="violent-sound-toggle" id="btnSonido" title="Activar/silenciar sonido">
                    <i class="fa-solid fa-volume-high"></i>
                </button>

                <!-- TERMÓMETRO ANIMADO -->

                <div class="violent-thermo-section">

                    <p class="violent-thermo-caption" id="thermoCaption">Procesando tus respuestas...</p>

                    <div class="violent-thermo-wrap">

                        <div class="violent-thermo-marks">
                            <span class="mark" data-mark="100">Crítico</span>
                            <span class="mark" data-mark="75">Alto</span>
                            <span class="mark" data-mark="50">Medio</span>
                            <span class="mark" data-mark="25">Sin riesgo</span>
                        </div>

                        <div class="violent-thermo">
                            <div class="violent-thermo-tube">
                                <div class="violent-thermo-mask" id="thermoMask"></div>
                            </div>
                            <div class="violent-thermo-bulb" id="thermoBulb">
                                <div class="violent-thermo-bulb-inner"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- DETALLE (aparece después de la animación) -->

                <div class="violent-result-details" id="resultDetails">

                    <div class="violent-result-badge" id="resultBadge">Sin riesgo</div>

                    <h2 id="resultTitulo">Resultado</h2>
                    <p id="resultTexto"></p>

                    <div class="violent-result-box">
                        <h4><i class="fa-solid fa-lightbulb"></i> Reflexiona</h4>
                        <p id="resultReflexiona"></p>
                    </div>

                    <div class="violent-result-box">
                        <h4><i class="fa-solid fa-list-check"></i> Recomendaciones</h4>
                        <ul id="resultRecomendaciones"></ul>
                    </div>

                    <div class="violent-resources" id="resultRecursos">

                        <h4>Líneas y rutas de apoyo</h4>

                        <div class="violent-resource-grid">

                            <a href="#" class="violent-resource-btn">
                                <i class="fa-solid fa-venus"></i>
                                Unidad de Género
                            </a>

                            <a href="#" class="violent-resource-btn">
                                <i class="fa-solid fa-brain"></i>
                                Consejería psicológica
                            </a>

                            <a href="#" class="violent-resource-btn">
                                <i class="fa-solid fa-map-location-dot"></i>
                                Ruta institucional UCEVA
                            </a>

                            <a href="tel:123" class="violent-resource-btn urgent">
                                <i class="fa-solid fa-phone"></i>
                                Línea de emergencia: 123
                            </a>

                        </div>

                    </div>

                    <button type="button" class="violent-btn-secondary" id="btnRepetir">
                        Realizar otro ejercicio
                    </button>

                    <p class="violent-disclaimer">
                        Este resultado tiene un carácter orientador y educativo. No constituye un diagnóstico
                        psicológico, psiquiátrico o médico, ni reemplaza la valoración de un profesional de la salud.
                    </p>

                </div>

            </div>

        </div>

    </div>
</section>

<!-- DATOS PARA JAVASCRIPT (ahora vienen armados desde ViolentometroController@index) -->

<script>
    window.violentometroData = @json($violentometroData);
</script>

@endsection

@push('scripts')
<script src="{{ mix('js/violentometro.js') }}" defer></script>
@endpush
