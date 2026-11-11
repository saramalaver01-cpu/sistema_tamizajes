
@extends('admin.app')

@section('title', 'Panel Administrativo | Sistema de Tamizajes')

@section('page-title', 'Panel Administrativo')

@section('content')

<style>
    /* CONTENEDOR PRINCIPAL */
    .dashboard-bienvenida {
        max-width: 1150px;
        margin: 0 auto;
    }

    /* ENCABEZADO DE BIENVENIDA */
    .dashboard-hero {
        position: relative;
        overflow: hidden;
        background: linear-gradient(
            115deg,
            #172d70 0%,
            #243b83 55%,
            #5420a8 100%
        );
        border-radius: 20px;
        padding: 48px 45px;
        color: #ffffff;
        box-shadow: 0 10px 30px rgba(23,45,112,0.13);
    }

    .dashboard-hero::before {
        content: '';
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        right: -90px;
        top: -110px;
        background: rgba(255,255,255,0.07);
    }

    .dashboard-hero::after {
        content: '';
        position: absolute;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        right: 130px;
        bottom: -110px;
        background: rgba(255,255,255,0.05);
    }

    .dashboard-hero-content {
        position: relative;
        z-index: 1;
    }

    .dashboard-etiqueta {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 9px 15px;
        border-radius: 30px;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.22);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 22px;
    }

    .dashboard-hero h1 {
        font-family: 'Poppins', sans-serif;
        font-size: clamp(26px, 3vw, 36px);
        font-weight: 700;
        line-height: 1.3;
        color: #ffffff;
        margin: 0 0 15px;
    }

    .dashboard-hero p {
        max-width: 730px;
        font-size: 15px;
        line-height: 1.85;
        color: #e2e8ff;
        margin: 0;
    }

    .dashboard-institucion {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 25px;
        font-size: 13px;
        font-weight: 600;
        color: #ffffff;
    }

    /* TARJETAS INFORMATIVAS */
    .dashboard-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
        margin-top: 28px;
    }

    .dashboard-info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(20,30,60,0.045);
    }

    .dashboard-info-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 14px;
        font-size: 22px;
        margin-bottom: 19px;
    }

    .dashboard-info-icon.proposito {
        background: #f3ecff;
        color: #5420a8;
    }

    .dashboard-info-icon.privacidad {
        background: #dcfce7;
        color: #168a5b;
    }

    .dashboard-info-card h2 {
        font-family: 'Poppins', sans-serif;
        font-size: 18px;
        color: #172d70;
        margin: 0 0 12px;
    }

    .dashboard-info-card p {
        color: #64748b;
        font-size: 14px;
        line-height: 1.85;
        margin: 0;
    }

    /* MENSAJE INFERIOR */
    .dashboard-footer-message {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        margin-top: 30px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
        color: #64748b;
        font-size: 13px;
        text-align: center;
    }

    .dashboard-footer-message i {
        color: #5420a8;
        font-size: 17px;
    }

    /* ADAPTACIÓN A CELULARES */
    @media (max-width: 768px) {
        .dashboard-hero {
            padding: 32px 25px;
        }

        .dashboard-hero h1 {
            font-size: 25px;
        }

        .dashboard-hero p {
            font-size: 14px;
        }

        .dashboard-info-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .dashboard-info-card {
            padding: 24px;
        }

        .dashboard-footer-message {
            flex-direction: column;
        }
    }
</style>

<div class="dashboard-bienvenida">

    {{-- BIENVENIDA INSTITUCIONAL --}}
    <section class="dashboard-hero">

        <div class="dashboard-hero-content">

            <span class="dashboard-etiqueta">
                <i class="fa-solid fa-heart-pulse"></i>
                Bienestar Universitario · UCEVA
            </span>

            <h1>
                ¡Bienvenido al Sistema de Tamizajes!
            </h1>

            <p>
                Te damos la bienvenida al panel administrativo
                de la Plataforma Integral de Tamizaje y
                Autoconocimiento para el Bienestar Universitario.

                Este espacio ha sido diseñado para apoyar
                la gestión de instrumentos de evaluación,
                la organización de campañas institucionales
                y el seguimiento de información que contribuya
                al bienestar de la comunidad universitaria.
            </p>

            <div class="dashboard-institucion">
                <i class="fa-solid fa-building-columns"></i>
                Unidad Central del Valle del Cauca
            </div>

        </div>

    </section>

    {{-- INFORMACIÓN INSTITUCIONAL --}}
    <div class="dashboard-info-grid">

        {{-- PROPÓSITO --}}
        <section class="dashboard-info-card">

            <div class="dashboard-info-icon proposito">
                <i class="fa-solid fa-hand-holding-heart"></i>
            </div>

            <h2>Nuestro propósito</h2>

            <p>
                Promover el bienestar integral de la comunidad
                universitaria mediante herramientas digitales
                que faciliten el autoconocimiento, la identificación
                temprana de necesidades y la orientación preventiva,
                contribuyendo al fortalecimiento de las estrategias
                institucionales de acompañamiento.
            </p>

        </section>

        {{-- CONFIDENCIALIDAD --}}
        <section class="dashboard-info-card">

            <div class="dashboard-info-icon privacidad">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            <h2>Compromiso con la confidencialidad</h2>

            <p>
                La protección de la información de los participantes
                es un principio fundamental de la plataforma.

                El acceso administrativo a los resultados debe
                limitarse a información agregada y anonimizada,
                respetando la privacidad y la confidencialidad
                de las personas que participan en los tamizajes.
            </p>

        </section>

    </div>


</div>

@endsection
