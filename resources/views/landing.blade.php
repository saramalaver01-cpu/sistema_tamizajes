@extends('layouts.app')


@section('title', 'Inicio | SBE')


@section('content')


<!-- =====================================================
     HERO
====================================================== -->

<section class="hero" id="inicio">

    <div class="hero-container">


        <!-- TEXTO -->

        <div class="hero-content">

            <span class="hero-badge">

                <i class="fa-solid fa-heart"></i>

                Sistema de Bienestar Universitario

            </span>


            <h1 class="hero-title">

                Tu

                <span class="green">
                    bienestar
                </span>

                es nuestra

                <span class="purple">
                    prioridad.
                </span>

            </h1>


            <p class="hero-description">

                Un espacio de acompañamiento integral
                para tu desarrollo personal, académico
                y social durante tu vida universitaria.

            </p>


            <div class="hero-buttons">

                <a
                    href="#modulos"
                    class="btn-primary"
                >

                    Explorar herramientas

                    <i class="fa-solid fa-arrow-right"></i>

                </a>


                <a
                    href="#servicios"
                    class="btn-secondary"
                >

                    Conocer nuestros servicios

                </a>

            </div>

        </div>


        <!-- IMAGEN -->

        <div class="hero-image">

            <img
                src="{{ asset('images/hero-estudiantes.png') }}"
                alt="Estudiantes universitarios"
            >

        </div>

    </div>

</section>



<!-- =====================================================
     BENEFICIOS
====================================================== -->

<section class="benefits">

    <div class="benefits-grid">


        <article class="benefit-card">

            <div class="benefit-icon">

                <i class="fa-solid fa-heart"></i>

            </div>

            <h3>
                Bienestar integral
            </h3>

            <p>
                Acompañamiento universitario
                pensado para ti.
            </p>

        </article>


        <article class="benefit-card">

            <div class="benefit-icon">

                <i class="fa-solid fa-shield-heart"></i>

            </div>

            <h3>
                Espacio seguro
            </h3>

            <p>
                Orientación y apoyo para
                situaciones que necesites.
            </p>

        </article>


        <article class="benefit-card">

            <div class="benefit-icon">

                <i class="fa-solid fa-users"></i>

            </div>

            <h3>
                Acompañamiento
            </h3>

            <p>
                Recursos para fortalecer
                tu experiencia universitaria.
            </p>

        </article>


    </div>

</section>



<!-- =====================================================
     SERVICIOS
====================================================== -->

<section
    class="services section"
    id="servicios"
>

    <div class="container">


        <div class="section-header">

            <span>
                Nuestros servicios
            </span>

            <h2>
                Bienestar pensado para ti
            </h2>

            <p>
                Encuentra diferentes áreas de acompañamiento
                disponibles para la comunidad universitaria.
            </p>

        </div>


        <div class="services-grid">


            <!-- APOYO PSICOLÓGICO -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-brain"></i>

                </div>

                <h3>
                    Apoyo psicológico
                </h3>

                <p>
                    Espacios de orientación y acompañamiento
                    para el bienestar emocional.
                </p>

            </article>


            <!-- ORIENTACIÓN ACADÉMICA -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-graduation-cap"></i>

                </div>

                <h3>
                    Orientación académica
                </h3>

                <p>
                    Apoyo para afrontar retos y situaciones
                    relacionadas con tu proceso académico.
                </p>

            </article>


            <!-- BIENESTAR SOCIAL -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-people-group"></i>

                </div>

                <h3>
                    Bienestar social
                </h3>

                <p>
                    Acompañamiento para fortalecer la
                    convivencia y las relaciones universitarias.
                </p>

            </article>


            <!-- SALUD INTEGRAL -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-heart-pulse"></i>

                </div>

                <h3>
                    Salud integral
                </h3>

                <p>
                    Recursos y orientación relacionados
                    con el cuidado integral de la salud.
                </p>

            </article>


            <!-- CULTURA -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-palette"></i>

                </div>

                <h3>
                    Cultura
                </h3>

                <p>
                    Espacios y actividades para fortalecer
                    la expresión y participación cultural.
                </p>

            </article>


            <!-- DEPORTE -->

            <article class="service-card">

                <div class="service-icon">

                    <i class="fa-solid fa-person-running"></i>

                </div>

                <h3>
                    Deporte
                </h3>

                <p>
                    Alternativas para promover hábitos
                    saludables y actividad física.
                </p>

            </article>


        </div>

    </div>

</section>



<!-- =====================================================
     HERRAMIENTAS / TAMIZAJES
====================================================== -->

<section
    class="modules section"
    id="modulos"
>

    <div class="container">


        <div class="section-header">

            <span>
                Herramientas de bienestar
            </span>

            <h2>
                Conoce nuestros módulos
            </h2>

            <p>
                Accede a las herramientas diseñadas para
                identificar situaciones y orientar tu proceso
                de bienestar universitario.
            </p>

        </div>


        <div class="modules-grid">

    <!-- VIOLENTÓMETRO -->

    <article class="module-card green">

        <div class="module-bg-icon">
            <i class="fa-solid fa-shield-heart"></i>
        </div>

        <div class="module-card-icon">
            <i class="fa-solid fa-shield-heart"></i>
        </div>

        <h3>Violentómetro</h3>

        <p>
            Identifica diferentes manifestaciones de violencia
            y conoce cuándo buscar ayuda.
        </p>

        <div class="module-meta">
            <span><i class="fa-solid fa-clock"></i> ~5 min</span>
            <span><i class="fa-solid fa-list-check"></i> 21 preguntas</span>
        </div>

        <a href="{{ route('violentometro') }}" class="module-link">
            <span>Conocer el violentómetro</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>

        <div class="module-decoration"></div>
        <div class="module-decoration deco-2"></div>

    </article>

    <!-- BIENESTAR EMOCIONAL -->

    <article class="module-card purple">

        <div class="module-bg-icon">
            <i class="fa-solid fa-face-smile"></i>
        </div>

        <div class="module-card-icon">
            <i class="fa-solid fa-face-smile"></i>
        </div>

        <h3>Bienestar emocional</h3>

        <p>
            Responde el tamizaje para conocer aspectos
            relacionados con tu bienestar emocional.
        </p>

        <div class="module-meta">
            <span><i class="fa-solid fa-clock"></i> ~5 min</span>
            <span><i class="fa-solid fa-list-check"></i> 21 preguntas</span>
        </div>

          <a href="{{ route('bienestar.emocional') }}" class="module-link">
                    Conocer el bienestar emocional
                    <i class="fa-solid fa-arrow-right"></i>
                </a>

        <div class="module-decoration"></div>
        <div class="module-decoration deco-2"></div>

    </article>

    <!-- CARGA ACADÉMICA -->

    <article class="module-card blue">

        <div class="module-bg-icon">
            <i class="fa-solid fa-book-open"></i>
        </div>

        <div class="module-card-icon">
            <i class="fa-solid fa-book-open"></i>
        </div>

        <h3>Carga académica</h3>

        <p>
            Identifica cómo la carga académica puede estar
            influyendo en tu experiencia universitaria.
        </p>

        <div class="module-meta">
            <span><i class="fa-solid fa-clock"></i> ~5 min</span>
            <span><i class="fa-solid fa-list-check"></i> 18 preguntas</span>
        </div>

        <a href="{{ route('carga-academica') }}" class="module-link">
            <span>Conocer la carga académica</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>

        <div class="module-decoration"></div>
        <div class="module-decoration deco-2"></div>

    </article>

</div>

    </div>

</section>



<!-- =====================================================
     VIOLENTÓMETRO
====================================================== -->

<section
    class="violentometro section"
    id="violentometro"
>

    <div class="container">


        <div class="violentometro-container">


            <div class="violentometro-image">

                <img
                    src="{{ asset('images/termometro.png') }}"
                    alt="Violentómetro"
                >

            </div>


            <div class="violentometro-content">

                <h2>
                    ¿Reconoces las señales?
                </h2>


                <p>
                    El violentómetro es una herramienta
                    que permite identificar diferentes
                    manifestaciones de violencia y reconocer
                    cuándo una situación puede requerir
                    atención o apoyo profesional.
                </p>


                <div class="violentometro-note">

                    <strong>
                        Recuerda:
                    </strong>

                    identificar una situación de violencia
                    es un primer paso para buscar apoyo
                    y proteger tu bienestar.

                </div>


                

            </div>


        </div>

    </div>

</section>



<!-- =====================================================
     CTA
====================================================== -->

<section class="cta">

    <div class="cta-container">

        <h2>
            Tu bienestar también hace parte
            de tu formación.
        </h2>

        <p>
            Conoce las herramientas y servicios
            disponibles para acompañarte durante
            tu vida universitaria.
        </p>


        <a
            href="#modulos"
            class="btn-secondary"
        >

            Explorar herramientas

            <i class="fa-solid fa-arrow-right"></i>

        </a>

    </div>

</section>


@endsection