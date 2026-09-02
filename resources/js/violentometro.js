document.addEventListener('DOMContentLoaded', () => {

    const data = window.violentometroData;

    if (!data) return;

    let contextoSeleccionado = null;
    let totalPreguntas = 0;


    /*
    =========================================================
    ELEMENTOS
    =========================================================
    */

    const stepIntro      = document.getElementById('stepIntro');
    const stepContexto   = document.getElementById('stepContexto');
    const stepPreguntas  = document.getElementById('stepPreguntas');
    const stepResultado  = document.getElementById('stepResultado');

    const consentIntroCheck  = document.getElementById('consentIntroCheck');
    const btnContinuarIntro  = document.getElementById('btnContinuarIntro');

    const contextoGrid   = document.getElementById('contextoGrid');
    const consentCheck   = document.getElementById('consentCheck');
    const btnIniciar     = document.getElementById('btnIniciar');
    const dimensionesContainer = document.getElementById('dimensionesContainer');

    const progressFill = document.getElementById('violentProgressFill');
    const progressText = document.getElementById('violentProgressText');
    const warning       = document.getElementById('violentWarning');

    const btnEnviar         = document.getElementById('btnEnviar');
    const btnVolverContexto = document.getElementById('btnVolverContexto');
    const btnRepetir        = document.getElementById('btnRepetir');


    /*
    =========================================================
    PASO 0: INTRODUCCIÓN
    =========================================================
    */

    consentIntroCheck.addEventListener('change', () => {
        btnContinuarIntro.disabled = !consentIntroCheck.checked;
    });

    btnContinuarIntro.addEventListener('click', () => {

        stepIntro.style.display = 'none';
        stepContexto.style.display = 'block';

        progressFill.style.width = '50%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });


    /*
    =========================================================
    PASO 1: RENDER DE OPCIONES DE CONTEXTO
    =========================================================
    */

    data.contextos.forEach(contexto => {

        const card = document.createElement('button');

        card.type = 'button';
        card.className = 'violent-context-card';
        card.dataset.valor = contexto.valor;

        card.innerHTML = `
            <i class="fa-solid ${contexto.icono}"></i>
            <span>${contexto.etiqueta}</span>
        `;

        card.addEventListener('click', () => {

            contextoGrid.querySelectorAll('.violent-context-card').forEach(c => {
                c.classList.remove('selected');
            });

            card.classList.add('selected');

            contextoSeleccionado = contexto.valor;

            actualizarBotonIniciar();

        });

        contextoGrid.appendChild(card);

    });

    function actualizarBotonIniciar() {
        btnIniciar.disabled = !(contextoSeleccionado && consentCheck.checked);
    }

    consentCheck.addEventListener('change', actualizarBotonIniciar);


    /*
    =========================================================
    PASO 2: RENDER DINÁMICO DE PREGUNTAS SEGÚN CONTEXTO
    =========================================================
    */

    function renderPreguntas() {

        dimensionesContainer.innerHTML = '';

        const sujeto = data.sujetos[contextoSeleccionado];

        let contador = 0;

        data.dimensiones.forEach(dimension => {

            const seccion = document.createElement('div');
            seccion.className = 'violent-dimension';

            seccion.innerHTML = `<h3>${dimension.titulo}</h3>`;

            dimension.preguntas.forEach(preguntaTemplate => {

                contador++;

                const preguntaTexto = preguntaTemplate.replace('{sujeto}', sujeto);

                const preguntaDiv = document.createElement('div');
                preguntaDiv.className = 'violent-question';
                preguntaDiv.dataset.index = contador - 1;

                let opcionesHtml = '';

                data.opciones.forEach((opcion, i) => {
                    opcionesHtml += `
                        <label class="violent-option">
                            <input type="radio" name="respuesta_${contador - 1}" value="${i + 1}">
                            <span class="violent-radio-circle"></span>
                            <span class="violent-option-label">${opcion}</span>
                        </label>
                    `;
                });

                preguntaDiv.innerHTML = `
                    <div class="violent-question-text">
                        <span class="violent-question-number">${contador}</span>
                        ${preguntaTexto}
                    </div>
                    <div class="violent-options">
                        ${opcionesHtml}
                    </div>
                `;

                seccion.appendChild(preguntaDiv);

            });

            dimensionesContainer.appendChild(seccion);

        });

        totalPreguntas = contador;

        actualizarProgreso();

    }


    /*
    =========================================================
    NAVEGACIÓN ENTRE PASOS
    =========================================================
    */

    btnIniciar.addEventListener('click', () => {

        renderPreguntas();

        stepContexto.style.display = 'none';
        stepPreguntas.style.display = 'block';

        progressFill.style.width = '75%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });

    btnVolverContexto.addEventListener('click', () => {

        stepPreguntas.style.display = 'none';
        stepContexto.style.display = 'block';

        progressFill.style.width = '50%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });

    btnRepetir.addEventListener('click', () => {

        contextoSeleccionado = null;
        consentCheck.checked = false;

        contextoGrid.querySelectorAll('.violent-context-card').forEach(c => {
            c.classList.remove('selected');
        });

        actualizarBotonIniciar();

        stepResultado.style.display = 'none';
        stepContexto.style.display = 'block';

        progressFill.style.width = '50%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });


    /*
    =========================================================
    PROGRESO Y SELECCIÓN VISUAL
    =========================================================
    */

    function actualizarProgreso() {

        const preguntas = dimensionesContainer.querySelectorAll('.violent-question');

        let respondidas = 0;

        preguntas.forEach(pregunta => {

            const marcada = pregunta.querySelector('input[type="radio"]:checked');

            pregunta.classList.toggle('answered', !!marcada);

            if (marcada) respondidas++;

        });

        progressText.textContent = `${respondidas} de ${totalPreguntas} respondidas`;

        return respondidas;

    }

    dimensionesContainer.addEventListener('change', (event) => {

        if (event.target.type !== 'radio') return;

        const opcionesDeLaPregunta = event.target.closest('.violent-question').querySelectorAll('.violent-option');

        opcionesDeLaPregunta.forEach(o => o.classList.remove('selected'));

        event.target.closest('.violent-option').classList.add('selected');

        actualizarProgreso();

        warning.classList.remove('show');

    });


    /*
    =========================================================
    SONIDO — tonos suaves, nunca de alarma
    =========================================================
    */

    let sonidoActivo = true;
    let audioCtx = null;

    const btnSonido = document.getElementById('btnSonido');

    if (btnSonido) {

        btnSonido.addEventListener('click', () => {

            sonidoActivo = !sonidoActivo;

            btnSonido.innerHTML = sonidoActivo
                ? '<i class="fa-solid fa-volume-high"></i>'
                : '<i class="fa-solid fa-volume-xmark"></i>';

        });

    }

    function getAudioCtx() {

        if (!sonidoActivo) return null;

        if (!audioCtx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            audioCtx = new AudioCtx();
        }

        return audioCtx;

    }

    function tono(frecuencia, duracion = 0.18, volumen = 0.09, retardo = 0) {

        const ctx = getAudioCtx();

        if (!ctx) return;

        const oscillator = ctx.createOscillator();
        const gain = ctx.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.value = frecuencia;

        const inicio = ctx.currentTime + retardo;

        gain.gain.setValueAtTime(0, inicio);
        gain.gain.linearRampToValueAtTime(volumen, inicio + duracion * 0.25);
        gain.gain.linearRampToValueAtTime(0, inicio + duracion);

        oscillator.connect(gain);
        gain.connect(ctx.destination);

        oscillator.start(inicio);
        oscillator.stop(inicio + duracion + 0.05);

    }

    function tonoTick() {
        tono(700, 0.09, 0.06);
    }

    function tonoFinal(nivel) {

        const secuencias = {
            sin_riesgo: [523, 659],
            medio: [440],
            alto: [392, 329],
            critico: [294, 246],
        };

        const notas = secuencias[nivel] || [440];

        notas.forEach((frecuencia, i) => {
            tono(frecuencia, 0.5, 0.1, i * 0.3);
        });

    }


    /*
    =========================================================
    TERMÓMETRO ANIMADO
    =========================================================
    */

    function calcularPorcentaje(nivel, puntaje) {

        let porcentaje = ((puntaje - 21) / (105 - 21)) * 100;

        porcentaje = Math.max(0, Math.min(100, porcentaje));

        const pisos  = { sin_riesgo: 0,  medio: 26, alto: 52, critico: 78 };
        const techos = { sin_riesgo: 25, medio: 51, alto: 77, critico: 100 };

        porcentaje = Math.max(porcentaje, pisos[nivel] ?? 0);
        porcentaje = Math.min(porcentaje, techos[nivel] ?? 100);

        return porcentaje;

    }

    function animarTermometro(nivel, puntaje, alTerminar) {

        const mask    = document.getElementById('thermoMask');
        const bulbo   = document.getElementById('thermoBulb');
        const caption = document.getElementById('thermoCaption');
        const marks   = document.querySelectorAll('.violent-thermo-marks .mark');

        const objetivo = calcularPorcentaje(nivel, puntaje);

        const colores = {
            sin_riesgo: '#94a3b8',
            medio: '#f59e0b',
            alto: '#ea580c',
            critico: '#dc2626',
        };

        bulbo.style.setProperty('--bulb-color', colores[nivel]);

        const duracion = 2200; // ms
        const inicio = performance.now();

        const marcasDisparadas = new Set();

        function paso(ahora) {

            const transcurrido = ahora - inicio;
            const progreso = Math.min(transcurrido / duracion, 1);

            const progresoSuave = 1 - Math.pow(1 - progreso, 3);

            const actual = progresoSuave * objetivo;

            mask.style.height = (100 - actual) + '%';

            marks.forEach(mark => {

                const valorMarca = parseFloat(mark.dataset.mark);

                if (actual >= valorMarca && !marcasDisparadas.has(valorMarca) && valorMarca <= objetivo + 1) {

                    marcasDisparadas.add(valorMarca);

                    mark.classList.add('reached');

                    tonoTick();

                }

            });

            if (progreso < 1) {

                requestAnimationFrame(paso);

            } else {

                bulbo.classList.add('activo');

                caption.textContent = 'Resultado calculado';

                tonoFinal(nivel);

                setTimeout(alTerminar, 400);

            }

        }

        requestAnimationFrame(paso);

    }


    /*
    =========================================================
    ENVÍO Y RESULTADO
    =========================================================
    */

    btnEnviar.addEventListener('click', async () => {

        const respondidas = actualizarProgreso();

        if (respondidas < totalPreguntas) {

            warning.classList.add('show');

            const primeraSinResponder = dimensionesContainer.querySelector('.violent-question:not(.answered)');

            if (primeraSinResponder) {
                primeraSinResponder.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            return;

        }

        const respuestas = [];

        for (let i = 0; i < totalPreguntas; i++) {
            const marcada = dimensionesContainer.querySelector(`input[name="respuesta_${i}"]:checked`);
            respuestas.push(parseInt(marcada.value, 10));
        }

        btnEnviar.disabled = true;
        btnEnviar.innerHTML = 'Calculando... <i class="fa-solid fa-spinner fa-spin"></i>';

        try {

            const response = await fetch(data.evaluarUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data.csrf,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    contexto: contextoSeleccionado,
                    respuestas: respuestas,
                }),
            });

            const resultado = await response.json();

            mostrarResultado(resultado);

        } catch (error) {

            alert('Ocurrió un error al procesar tu resultado. Intenta de nuevo.');

        } finally {

            btnEnviar.disabled = false;
            btnEnviar.innerHTML = 'Ver mi resultado <i class="fa-solid fa-arrow-right"></i>';

        }

    });

    function mostrarResultado(resultado) {

        const mask    = document.getElementById('thermoMask');
        const bulbo   = document.getElementById('thermoBulb');
        const details = document.getElementById('resultDetails');
        const caption = document.getElementById('thermoCaption');

        mask.style.height = '100%';
        bulbo.classList.remove('activo');
        details.classList.remove('visible');
        caption.textContent = 'Procesando tus respuestas...';

        document.querySelectorAll('.violent-thermo-marks .mark').forEach(m => {
            m.classList.remove('reached');
        });

        const resultCard = document.getElementById('resultCard');
        resultCard.className = 'violent-result-card nivel-' + resultado.color;

        document.getElementById('resultBadge').textContent = resultado.etiqueta;
        document.getElementById('resultTexto').textContent = resultado.resultado;
        document.getElementById('resultReflexiona').textContent = resultado.reflexiona;

        const lista = document.getElementById('resultRecomendaciones');
        lista.innerHTML = '';

        resultado.recomendaciones.forEach(rec => {
            const li = document.createElement('li');
            li.textContent = rec;
            lista.appendChild(li);
        });

        document.getElementById('resultRecursos').style.display =
            (resultado.nivel === 'alto' || resultado.nivel === 'critico') ? 'block' : 'none';

        stepPreguntas.style.display = 'none';
        stepResultado.style.display = 'block';

        progressFill.style.width = '100%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

        animarTermometro(resultado.nivel, resultado.puntaje, () => {
            details.classList.add('visible');
        });

    }

});