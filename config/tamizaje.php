<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Llave para anonimizar el correo institucional (HMAC-SHA256)
    |--------------------------------------------------------------------------
    | Debe ser secreta, larga y aleatoria. Una vez haya datos reales NO se debe
    | cambiar ni perder: los hashes dejarían de coincidir y los participantes
    | perderían el vínculo con su historial.
    */
    'anon_hash_key' => env('ANON_HASH_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Opciones del formulario sociodemográfico (guía del proyecto, sección 6.3)
    |--------------------------------------------------------------------------
    | Para cambiar una opción basta con editar este archivo.
    */
    'sociodemografico' => [

        'roles' => ['Estudiante', 'Docente', 'Funcionario', 'Contratista', 'Otro'],

        // Roles a los que se les pregunta la dependencia (en lugar de facultad/programa)
        'roles_con_dependencia' => ['Funcionario', 'Contratista', 'Otro'],

        // TODO: completar con las facultades y programas reales de la UCEVA
        'facultades' => [
            'Facultad de Ingeniería' => [
                'Ingeniería de Sistemas',
            ],
        ],

        // TODO: confirmar las jornadas reales de la institución
        'jornadas' => ['Diurna', 'Nocturna', 'Fin de semana'],

        'sexo' => ['Mujer', 'Hombre', 'Intersexual', 'Prefiero no responder'],

        'identidad_genero' => [
            'Mujer',
            'Hombre',
            'Mujer trans',
            'Hombre trans',
            'No binario',
            'Otra',
            'Prefiero no responder',
        ],

        'orientacion_sexual' => [
            'Heterosexual',
            'Gay',
            'Lesbiana',
            'Bisexual',
            'Pansexual',
            'Asexual',
            'Otra',
            'Prefiero no responder',
        ],

        'estado_civil' => [
            'Soltero(a)',
            'Casado(a)',
            'Unión libre',
            'Separado(a) o divorciado(a)',
            'Viudo(a)',
        ],

        'nivel_educativo' => [
            'Bachiller',
            'Técnico o tecnólogo',
            'Profesional',
            'Especialización',
            'Maestría',
            'Doctorado',
        ],

        // Solo son sugerencias del campo de texto (se puede escribir otro municipio)
        'municipios_sugeridos' => [
            'Tuluá', 'Buga', 'Cali', 'Andalucía', 'Bugalagrande', 'Zarzal',
            'Sevilla', 'Trujillo', 'Riofrío', 'Yotoco', 'San Pedro', 'Roldanillo',
            'Palmira', 'Cartago',
        ],
    ],
];
