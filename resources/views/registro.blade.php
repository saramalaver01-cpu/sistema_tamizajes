<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Completa tu registro | SBE</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    {{-- Reutiliza los estilos del login para mantener la misma identidad visual --}}
    <link rel="stylesheet" href="{{ mix('css/login.css') }}">

    <style>
        .registro-card { max-width: 760px; width: 100%; }

        .registro-section {
            margin: 26px 0 12px;
            font-family: 'Poppins', sans-serif;
            font-size: 1.05rem;
            font-weight: 600;
        }

        .registro-consent {
            max-height: 230px;
            overflow-y: auto;
            padding: 14px 18px;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 12px;
            background: rgba(255, 255, 255, .7);
            font-size: .9rem;
            line-height: 1.55;
        }
        .registro-consent ul { margin: 8px 0 0 18px; padding: 0; }
        .registro-consent li { margin-bottom: 6px; }

        .registro-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 14px 0 4px;
            font-size: .92rem;
            line-height: 1.45;
        }
        .registro-check input { margin-top: 3px; }

        .registro-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }
        @media (max-width: 640px) {
            .registro-grid { grid-template-columns: 1fr; }
        }

        .registro-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid rgba(0, 0, 0, .2);
            border-radius: 12px;
            background: #fff;
            color: #1f2933;
            font: inherit;
        }
        .registro-control:focus {
            outline: 2px solid currentColor;
            outline-offset: 2px;
        }

        .registro-error { display: block; margin-top: 4px; color: #b42318; font-size: .82rem; }
        .registro-hint  { margin: 0 0 12px; font-size: .85rem; opacity: .8; }

        [hidden] { display: none !important; }
    </style>

</head>
<body>

    <div class="login-bg">
        <div class="login-blob blob-1"></div>
        <div class="login-blob blob-2"></div>
        <div class="login-blob blob-3"></div>
    </div>

    <div class="login-wrap">

        <div class="login-card registro-card">

            <div class="login-logo">
                <img src="{{ asset('images/logo-sibe.png') }}" alt="SBE">
            </div>

            <div class="login-heading">
                <h1>Completa tu registro</h1>
                <p>Es la primera vez que ingresas. Lee el consentimiento y responde unas preguntas de caracterización. No pedimos datos que te identifiquen.</p>
            </div>

            @if ($errors->any())
                <div class="login-alert-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Revisa los campos marcados en rojo para poder continuar.
                </div>
            @endif

            <form method="POST" action="{{ route('registro.estudiante.guardar') }}">
                @csrf

                {{-- ===================== CONSENTIMIENTO INFORMADO ===================== --}}

                <h2 class="registro-section" style="margin-top: 0;">Consentimiento informado</h2>

                <div class="registro-consent" tabindex="0">
                    <ul>
                        <li>La participación es completamente voluntaria.</li>
                        <li>Puedes abandonar el ejercicio en cualquier momento. La información registrada hasta ese momento quedará almacenada con el avance alcanzado.</li>
                        <li>La información será tratada de manera confidencial y de conformidad con la normatividad colombiana vigente sobre protección de datos personales y hábeas data.</li>
                        <li>Tu correo institucional se usa exclusivamente para validar el acceso y verificar tu vinculación con la institución. No se emplea para identificarte ni para asociar tus respuestas con tu identidad.</li>
                        <li>No se solicitarán datos que permitan identificarte directamente, como nombre, número de documento, código institucional, dirección o teléfono.</li>
                        <li>Los datos se usarán únicamente para la retroalimentación al usuario, la generación de estadísticas institucionales agregadas y anonimizadas, y el fortalecimiento de las estrategias de promoción, prevención y bienestar universitario.</li>
                        <li>La información no se usará con fines comerciales ni se compartirá con terceros no autorizados, salvo los casos previstos por la legislación vigente.</li>
                        <li>Los instrumentos corresponden a herramientas de tamizaje y autoconocimiento. Sus resultados son orientadores y educativos: no constituyen un diagnóstico psicológico, psiquiátrico o médico ni reemplazan la valoración de un profesional de la salud.</li>
                    </ul>
                </div>

                <label class="registro-check" for="consentimiento">
                    <input
                        type="checkbox"
                        id="consentimiento"
                        name="consentimiento"
                        value="1"
                        {{ old('consentimiento') ? 'checked' : '' }}
                        required
                    >
                    <span>Declaro que he leído y comprendido la información anterior, autorizo el tratamiento de los datos personales de conformidad con las finalidades aquí descritas y acepto participar de manera libre y voluntaria en este ejercicio.</span>
                </label>
                @error('consentimiento')
                    <span class="registro-error">{{ $message }}</span>
                @enderror

                {{-- ===================== ROL INSTITUCIONAL ===================== --}}

                <h2 class="registro-section">Rol institucional</h2>

                <div class="registro-grid">

                    <div class="login-field registro-full" style="grid-column: 1 / -1;">
                        <label for="rol_institucional">¿Cuál es tu rol en la institución?</label>
                        <select id="rol_institucional" name="rol_institucional" class="registro-control" required>
                            <option value="">Selecciona…</option>
                            @foreach ($opciones['roles'] as $rol)
                                <option value="{{ $rol }}" {{ old('rol_institucional') === $rol ? 'selected' : '' }}>{{ $rol }}</option>
                            @endforeach
                        </select>
                        @error('rol_institucional')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- Solo estudiantes --}}
                <div id="bloqueEstudiante" class="registro-grid" style="margin-top: 16px;" hidden>

                    <div class="login-field">
                        <label for="facultad">Facultad</label>
                        <select id="facultad" name="facultad" class="registro-control">
                            <option value="">Selecciona…</option>
                            @foreach ($opciones['facultades'] as $facultad => $programas)
                                <option value="{{ $facultad }}" {{ old('facultad') === $facultad ? 'selected' : '' }}>{{ $facultad }}</option>
                            @endforeach
                        </select>
                        @error('facultad')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="programa_academico">Programa académico</label>
                        <select id="programa_academico" name="programa_academico" class="registro-control">
                            <option value="">Selecciona…</option>
                        </select>
                        @error('programa_academico')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="semestre">Semestre</label>
                        <select id="semestre" name="semestre" class="registro-control">
                            <option value="">Selecciona…</option>
                            @foreach (range(1, 12) as $n)
                                <option value="{{ $n }}" {{ (string) old('semestre') === (string) $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                        @error('semestre')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="jornada">Jornada</label>
                        <select id="jornada" name="jornada" class="registro-control">
                            <option value="">Selecciona…</option>
                            @foreach ($opciones['jornadas'] as $jornada)
                                <option value="{{ $jornada }}" {{ old('jornada') === $jornada ? 'selected' : '' }}>{{ $jornada }}</option>
                            @endforeach
                        </select>
                        @error('jornada')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- Solo funcionario, contratista u otro --}}
                <div id="bloqueDependencia" class="registro-grid" style="margin-top: 16px;" hidden>

                    <div class="login-field" style="grid-column: 1 / -1;">
                        <label for="dependencia">Dependencia</label>
                        <input
                            type="text"
                            id="dependencia"
                            name="dependencia"
                            class="registro-control"
                            maxlength="150"
                            value="{{ old('dependencia') }}"
                        >
                        @error('dependencia')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                {{-- ===================== CARACTERIZACIÓN SOCIODEMOGRÁFICA ===================== --}}

                <h2 class="registro-section">Información sociodemográfica</h2>

                <p class="registro-hint">
                    En sexo, identidad de género y orientación sexual puedes elegir «Prefiero no responder».
                </p>

                @php
                    $campos = [
                        'sexo'               => 'Sexo',
                        'identidad_genero'   => 'Identidad de género',
                        'orientacion_sexual' => 'Orientación sexual',
                        'estado_civil'       => 'Estado civil',
                        'nivel_educativo'    => 'Nivel educativo alcanzado',
                    ];
                @endphp

                <div class="registro-grid">

                    <div class="login-field">
                        <label for="edad">Edad</label>
                        <input
                            type="number"
                            id="edad"
                            name="edad"
                            class="registro-control"
                            min="15"
                            max="100"
                            value="{{ old('edad') }}"
                            required
                        >
                        @error('edad')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                    @foreach ($campos as $name => $label)
                        <div class="login-field">
                            <label for="{{ $name }}">{{ $label }}</label>
                            <select id="{{ $name }}" name="{{ $name }}" class="registro-control" required>
                                <option value="">Selecciona…</option>
                                @foreach ($opciones[$name] as $opcion)
                                    <option value="{{ $opcion }}" {{ old($name) === $opcion ? 'selected' : '' }}>{{ $opcion }}</option>
                                @endforeach
                            </select>
                            @error($name)
                                <span class="registro-error">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach

                    <div class="login-field">
                        <label for="estrato">Estrato socioeconómico</label>
                        <select id="estrato" name="estrato" class="registro-control" required>
                            <option value="">Selecciona…</option>
                            @foreach (range(1, 6) as $n)
                                <option value="{{ $n }}" {{ (string) old('estrato') === (string) $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                        @error('estrato')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label for="municipio_residencia">Municipio de residencia</label>
                        <input
                            type="text"
                            id="municipio_residencia"
                            name="municipio_residencia"
                            class="registro-control"
                            list="listaMunicipios"
                            maxlength="100"
                            value="{{ old('municipio_residencia') }}"
                            required
                        >
                        <datalist id="listaMunicipios">
                            @foreach ($opciones['municipios_sugeridos'] as $municipio)
                                <option value="{{ $municipio }}"></option>
                            @endforeach
                        </datalist>
                        @error('municipio_residencia')
                            <span class="registro-error">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="login-submit-btn" style="margin-top: 26px;">
                    <span>Guardar y continuar</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>

            <a href="{{ route('login') }}" class="login-back-link">
                <i class="fa-solid fa-arrow-left"></i>
                Cancelar y volver al inicio de sesión
            </a>

        </div>

    </div>

    <script>
        (function () {
            const facultades = @json($opciones['facultades']);
            const rolesConDependencia = @json($opciones['roles_con_dependencia']);
            const programaAnterior = @json(old('programa_academico'));

            const rol = document.getElementById('rol_institucional');
            const bloqueEstudiante = document.getElementById('bloqueEstudiante');
            const bloqueDependencia = document.getElementById('bloqueDependencia');
            const facultad = document.getElementById('facultad');
            const programa = document.getElementById('programa_academico');

            // Muestra u oculta un bloque. Los campos ocultos se deshabilitan
            // para que el navegador no los exija ni los envíe.
            function mostrarBloque(bloque, mostrar) {
                bloque.hidden = !mostrar;
                bloque.querySelectorAll('select, input').forEach(function (campo) {
                    campo.disabled = !mostrar;
                    campo.required = mostrar;
                });
            }

            function cargarProgramas() {
                const lista = facultades[facultad.value] || [];
                programa.innerHTML = '<option value="">Selecciona…</option>';

                lista.forEach(function (nombre) {
                    const opcion = document.createElement('option');
                    opcion.value = nombre;
                    opcion.textContent = nombre;
                    if (nombre === programaAnterior) {
                        opcion.selected = true;
                    }
                    programa.appendChild(opcion);
                });
            }

            function actualizarRol() {
                mostrarBloque(bloqueEstudiante, rol.value === 'Estudiante');
                mostrarBloque(bloqueDependencia, rolesConDependencia.includes(rol.value));
            }

            rol.addEventListener('change', actualizarRol);
            facultad.addEventListener('change', cargarProgramas);

            cargarProgramas();
            actualizarRol();
        })();
    </script>

</body>
</html>
