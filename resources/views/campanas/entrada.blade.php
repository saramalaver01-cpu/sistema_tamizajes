
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $campana->nombre }} | Sistema de Tamizajes</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        :root {
            --azul: #172d70;
            --azul-oscuro: #10235b;
            --morado: #5420a8;
            --morado-claro: #7c3aed;
            --fondo: #f5f7fc;
            --texto: #172033;
            --gris: #64748b;
            --borde: #e2e8f0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--fondo);
            color: var(--texto);
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* ENCABEZADO INSTITUCIONAL */

        .header {
            background: linear-gradient(
                115deg,
                var(--azul-oscuro),
                var(--azul),
                var(--morado)
            );
            color: white;
            padding: 17px 24px;
        }

        .header-inner {
            max-width: 1120px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .marca {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .marca img {
            width: 76px;
            height: 50px;
            object-fit: contain;
            background: white;
            border-radius: 9px;
            padding: 7px;
        }

        .marca h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        .marca p {
            margin: 3px 0 0;
            color: #dbe5ff;
            font-size: 12px;
        }

        .header-etiqueta {
            padding: 9px 15px;
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
        }

        /* CONTENIDO */

        .contenedor {
            max-width: 1120px;
            margin: 36px auto;
            padding: 0 20px;
        }

        /* PRESENTACIÓN DE LA CAMPAÑA */

        .bienvenida {
            background: #fff;
            border: 1px solid var(--borde);
            border-top: 4px solid var(--morado);
            border-radius: 17px;
            padding: 32px;
            box-shadow: 0 5px 18px rgba(20,30,60,.05);
        }

        .estado {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #dcfce7;
            color: #15803d;
            padding: 7px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
        }

        .bienvenida h1 {
            color: var(--azul);
            font-size: clamp(23px, 3vw, 32px);
            margin: 18px 0 10px;
            line-height: 1.25;
        }

        .descripcion {
            color: var(--gris);
            line-height: 1.7;
            font-size: 14px;
        }

        .fechas {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            margin-top: 14px;
            padding: 11px 15px;
            background: #f4f1fc;
            border-radius: 10px;
            color: #5420a8;
            font-size: 13px;
            font-weight: 600;
        }

        /* SECCIÓN DE TAMIZAJES */

        .seccion {
            margin-top: 32px;
        }

        .seccion-titulo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 7px;
        }

        .seccion-titulo i {
            color: var(--morado);
            font-size: 20px;
        }

        .seccion-titulo h2 {
            margin: 0;
            color: var(--azul);
            font-size: 22px;
        }

        .seccion-descripcion {
            color: var(--gris);
            font-size: 14px;
            margin-bottom: 22px;
        }

        .modulos-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .modulo-card {
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid var(--borde);
            border-top: 4px solid var(--color-inicio);
            border-radius: 16px;
            padding: 24px;
            text-decoration: none;
            color: var(--texto);
            min-height: 250px;
            box-shadow: 0 4px 15px rgba(20,30,60,.05);
            transition: transform .2s, box-shadow .2s;
        }

        .modulo-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 28px rgba(20,30,60,.12);
        }

        .modulo-icono {
            width: 52px;
            height: 52px;
            border-radius: 13px;
            background: linear-gradient(
                135deg,
                var(--color-inicio),
                var(--color-fin)
            );
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            margin-bottom: 18px;
        }

        .modulo-card h3 {
            color: var(--azul);
            margin: 0 0 10px;
            font-size: 18px;
        }

        .modulo-card p {
            color: var(--gris);
            font-size: 13px;
            line-height: 1.7;
            margin: 0 0 20px;
            flex: 1;
        }

        .modulo-accion {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 1px solid #eef0f5;
            padding-top: 16px;
            color: var(--morado);
            font-size: 13px;
            font-weight: 700;
        }

        /* AVISO DE BIENESTAR */

        .aviso {
            margin-top: 28px;
            display: flex;
            gap: 15px;
            background: #eef2ff;
            border: 1px solid #dbe4fa;
            border-radius: 14px;
            padding: 20px;
        }

        .aviso-icono {
            color: var(--morado);
            font-size: 22px;
        }

        .aviso h3 {
            color: var(--azul);
            font-size: 15px;
            margin: 0 0 8px;
        }

        .aviso p {
            color: #475569;
            font-size: 13px;
            line-height: 1.7;
            margin: 0;
        }

        .sin-modulos {
            background: #fff;
            border: 1px dashed #d6c8f0;
            border-radius: 15px;
            padding: 35px;
            color: var(--gris);
            text-align: center;
        }

        footer {
            margin-top: 45px;
            padding: 28px 15px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }

        /* RESPONSIVE */

        @media (max-width: 640px) {
            .header {
                padding: 15px;
            }

            .header-etiqueta {
                display: none;
            }

            .marca img {
                width: 60px;
                height: 44px;
            }

            .marca h2 {
                font-size: 15px;
            }

            .contenedor {
                margin: 20px auto;
                padding: 0 14px;
            }

            .bienvenida {
                padding: 23px;
            }

            .modulos-grid {
                grid-template-columns: 1fr;
            }

            .modulo-card {
                min-height: 215px;
            }

            .aviso {
                padding: 16px;
            }
        }
    </style>
</head>

<body>

<header class="header">
    <div class="header-inner">

        <div class="marca">
            <img src="{{ asset('images/logo-sibe.png') }}"
                 alt="Logo del Sistema de Tamizajes">

            <div>
                <h2>Sistema de Tamizajes</h2>
                <p>Bienestar Universitario · UCEVA</p>
            </div>
        </div>

        <span class="header-etiqueta">
            <i class="fa-solid fa-heart-pulse"></i>
            Jornada institucional
        </span>

    </div>
</header>

<main class="contenedor">

    <section class="bienvenida">

        <span class="estado">
            <i class="fa-solid fa-circle-check"></i>
            Campaña en curso
        </span>

        <h1>{{ $campana->nombre }}</h1>

        @if($campana->descripcion)
        <p class="descripcion">
            {{ $campana->descripcion }}
        </p>
        @endif

        <p class="descripcion">
            Bienvenido a esta jornada de bienestar.
            Selecciona uno de los instrumentos de
            tamizaje disponibles para participar.
        </p>

        <div class="fechas">
            <i class="fa-regular fa-calendar"></i>
            Del {{ $campana->fecha_inicio->format('d/m/Y') }}
            al {{ $campana->fecha_fin->format('d/m/Y') }}
        </div>

    </section>

    <section class="seccion">

        <div class="seccion-titulo">
            <i class="fa-solid fa-clipboard-list"></i>
            <h2>Tamizajes disponibles</h2>
        </div>

        <p class="seccion-descripcion">
            Selecciona el instrumento que deseas realizar.
        </p>

        <div class="modulos-grid">

            @forelse($modulos as $modulo)

            @php
            $gradiente = $modulo->gradiente ?? [];
            $colorInicio = $gradiente['from'] ?? '#7c3aed';
            $colorFin = $gradiente['to'] ?? '#5420a8';
            @endphp

            <a href="{{ $modulo->url_publica }}"
               class="modulo-card"
               style="
                       --color-inicio: {{ $colorInicio }};
                       --color-fin: {{ $colorFin }};
                   ">

                <div class="modulo-icono">
                    <i class="fa-solid {{ $modulo->icono ?? 'fa-clipboard-list' }}"></i>
                </div>

                <h3>{{ $modulo->nombre }}</h3>

                <p>
                    {{ $modulo->descripcion ?? 'Instrumento de evaluación y autoconocimiento.' }}
                </p>

                <span class="modulo-accion">
                        Iniciar tamizaje
                        <i class="fa-solid fa-arrow-right"></i>
                    </span>

            </a>

            @empty

            <div class="sin-modulos">
                <i class="fa-solid fa-circle-info"></i>
                <p>
                    No hay instrumentos habilitados
                    actualmente en esta campaña.
                </p>
            </div>

            @endforelse

        </div>

    </section>

    <div class="aviso">

        <i class="fa-solid fa-shield-heart aviso-icono"></i>

        <div>
            <h3>Tu bienestar es importante</h3>
            <p>
                La participación es voluntaria.
                Los instrumentos tienen una finalidad
                preventiva y orientativa; no sustituyen
                una valoración médica o psicológica.
                Antes de responder, se debe presentar
                el consentimiento informado correspondiente.
            </p>
        </div>

    </div>

</main>

<footer>
    Sistema de Tamizajes · Bienestar Universitario · UCEVA
</footer>

</body>
</html>
