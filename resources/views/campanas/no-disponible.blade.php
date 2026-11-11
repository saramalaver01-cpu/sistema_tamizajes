
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Campaña no disponible</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f5f7fc;
            color: #172033;
        }

        .tarjeta {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 35px;
            max-width: 520px;
            width: 100%;
            text-align: center;
            box-shadow: 0 8px 30px rgba(0,0,0,.05);
        }

        h1 { color: #5420a8; }
        p { color: #64748b; line-height: 1.7; }

        .estado {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 20px;
            background: #fef3c7;
            color: #92400e;
            font-weight: bold;
        }
    </style>
</head>

<body>
<main class="tarjeta">
        <span class="estado">
            {{ $campana->estado_etiqueta }}
        </span>

    <h1>{{ $campana->nombre }}</h1>

    <h2>Campaña no disponible</h2>

    <p>
        Esta jornada no está habilitada actualmente.
        Puede encontrarse programada, pausada
        o finalizada.
    </p>

    <p>
        Período:
        {{ $campana->fecha_inicio->format('d/m/Y') }}
        al
        {{ $campana->fecha_fin->format('d/m/Y') }}
    </p>
</main>
</body>
</html>
