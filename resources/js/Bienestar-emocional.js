document.addEventListener('DOMContentLoaded', () => {

    const data = window.bienestarData;

    if (!data) return;

    let totalPreguntas = 0;


    /*
    =========================================================
    ELEMENTOS
    =========================================================
    */

    const stepIntro     = document.getElementById('stepIntro');
    const stepPreguntas = document.getElementById('stepPreguntas');
    const stepResultado = document.getElementById('stepResultado');

    const consentIntroCheck = document.getElementById('consentIntroCheck');
    const btnContinuarIntro = document.getElementById('btnContinuarIntro');
    const btnVolverIntro    = document.getElementById('btnVolverIntro');

    const dimensionesContainer = document.getElementById('dimensionesContainer');

    const progressFill = document.getElementById('bienestarProgressFill');
    const progressText = document.getElementById('bienestarProgressText');
    const warning       = document.getElementById('bienestarWarning');

    const btnEnviar  = document.getElementById('btnEnviar');
    const btnRepetir = document.getElementById('btnRepetir');


    /*
    =========================================================
    PASO 0: INTRODUCCIÓN
    =========================================================
    */

    consentIntroCheck.addEventListener('change', () => {
        btnContinuarIntro.disabled = !consentIntroCheck.checked;
    });

    btnContinuarIntro.addEventListener('click', () => {

        renderPreguntas();

        stepIntro.style.display = 'none';
        stepPreguntas.style.display = 'block';

        progressFill.style.width = '66%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });

    btnVolverIntro.addEventListener('click', () => {

        stepPreguntas.style.display = 'none';
        stepIntro.style.display = 'block';

        progressFill.style.width = '33%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

    });


    /*
    =========================================================
    RENDER DE PREGUNTAS
    =========================================================
    */

    function renderPreguntas() {

        if (dimensionesContainer.dataset.rendered === 'true') return;

        let contador = 0;

        data.dimensiones.forEach(dimension => {

            const seccion = document.createElement('div');
            seccion.className = 'bienestar-dimension';

            seccion.innerHTML = `
                <h3>${dimension.titulo}</h3>
                <p class="bienestar-dimension-desc">${dimension.descripcion}</p>
            `;

            dimension.preguntas.forEach(preguntaTexto => {

                contador++;

                const preguntaDiv = document.createElement('div');
                preguntaDiv.className = 'bienestar-question';
                preguntaDiv.dataset.index = contador - 1;

                let opcionesHtml = '';

                data.opciones.forEach((opcion, i) => {
                    opcionesHtml += `
                        <label class="bienestar-option">
                            <input type="radio" name="respuesta_${contador - 1}" value="${i + 1}">
                            <span class="bienestar-radio-circle"></span>
                            <span class="bienestar-option-label">${opcion}</span>
                        </label>
                    `;
                });

                preguntaDiv.innerHTML = `
                    <div class="bienestar-question-text">
                        <span class="bienestar-question-number">${contador}</span>
                        ${preguntaTexto}
                    </div>
                    <div class="bienestar-options">
                        ${opcionesHtml}
                    </div>
                `;

                seccion.appendChild(preguntaDiv);

            });

            dimensionesContainer.appendChild(seccion);

        });

        totalPreguntas = contador;

        dimensionesContainer.dataset.rendered = 'true';

        actualizarProgreso();

    }


    /*
    =========================================================
    PROGRESO Y SELECCIÓN VISUAL
    =========================================================
    */

    function actualizarProgreso() {

        const preguntas = dimensionesContainer.querySelectorAll('.bienestar-question');

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

        const opcionesDeLaPregunta = event.target.closest('.bienestar-question').querySelectorAll('.bienestar-option');

        opcionesDeLaPregunta.forEach(o => o.classList.remove('selected'));

        event.target.closest('.bienestar-option').classList.add('selected');

        actualizarProgreso();

        warning.classList.remove('show');

    });


    /*
    =========================================================
    SONIDO — tonos suaves
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

        // Ascendente y cálido para los niveles positivos, más grave y suave
        // (nunca de alarma) para los que requieren más atención.
        const secuencias = {
            fortalecido: [523, 659],
            estable: [493, 587],
            seguimiento: [440],
            atencion: [349, 294],
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

        const pisos  = { fortalecido: 0,  estable: 26, seguimiento: 52, atencion: 78 };
        const techos = { fortalecido: 25, estable: 51, seguimiento: 77, atencion: 100 };

        porcentaje = Math.max(porcentaje, pisos[nivel] ?? 0);
        porcentaje = Math.min(porcentaje, techos[nivel] ?? 100);

        return porcentaje;

    }

    function animarTermometro(nivel, puntaje, alTerminar) {

        const mask    = document.getElementById('thermoMask');
        const bulbo   = document.getElementById('thermoBulb');
        const caption = document.getElementById('thermoCaption');
        const marks   = document.querySelectorAll('.bienestar-thermo-marks .mark');

        const objetivo = calcularPorcentaje(nivel, puntaje);

        const colores = {
            fortalecido: '#198754',
            estable: '#2563eb',
            seguimiento: '#f59e0b',
            atencion: '#ea580c',
        };

        bulbo.style.setProperty('--bulb-color', colores[nivel]);

        const duracion = 2200;
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

            const primeraSinResponder = dimensionesContainer.querySelector('.bienestar-question:not(.answered)');

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
                    respuestas: respuestas,
                }),
            });

            const resultado = await response.json();

            mostrarResultado(resultado);

        } catch (error) {

            alert('Ocurrió un error al procesar tu resultado. Intenta de nuevo.');

        } finally {

            btnEnviar.disabled = false;
            btnEnviar.innerHTML = 'Enviar respuestas <i class="fa-solid fa-arrow-right"></i>';

        }

    });

    btnRepetir.addEventListener('click', () => {

        document.querySelectorAll('#dimensionesContainer input[type="radio"]:checked').forEach(input => {
            input.checked = false;
        });

        document.querySelectorAll('.bienestar-option.selected').forEach(o => o.classList.remove('selected'));

        actualizarProgreso();

        stepResultado.style.display = 'none';
        stepIntro.style.display = 'block';

        consentIntroCheck.checked = false;
        btnContinuarIntro.disabled = true;

        progressFill.style.width = '33%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

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

        document.querySelectorAll('.bienestar-thermo-marks .mark').forEach(m => {
            m.classList.remove('reached');
        });

        const resultCard = document.getElementById('resultCard');
        resultCard.className = 'bienestar-result-card nivel-' + resultado.color;

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
            (resultado.nivel === 'seguimiento' || resultado.nivel === 'atencion') ? 'block' : 'none';

        stepPreguntas.style.display = 'none';
        stepResultado.style.display = 'block';

        progressFill.style.width = '100%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

        animarTermometro(resultado.nivel, resultado.puntaje, () => {
            details.classList.add('visible');
        });

    }

});