document.addEventListener('DOMContentLoaded', () => {

    const data = window.cargaData;

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

    const progressFill = document.getElementById('cargaProgressFill');
    const progressText = document.getElementById('cargaProgressText');
    const warning       = document.getElementById('cargaWarning');

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
            seccion.className = 'carga-dimension';

            seccion.innerHTML = `
                <h3>${dimension.titulo}</h3>
                <p class="carga-dimension-desc">${dimension.descripcion}</p>
            `;

            dimension.preguntas.forEach(preguntaTexto => {

                contador++;

                const preguntaDiv = document.createElement('div');
                preguntaDiv.className = 'carga-question';
                preguntaDiv.dataset.index = contador - 1;

                let opcionesHtml = '';

                data.opciones.forEach((opcion, i) => {
                    opcionesHtml += `
                        <label class="carga-option">
                            <input type="radio" name="respuesta_${contador - 1}" value="${i + 1}">
                            <span class="carga-radio-circle"></span>
                            <span class="carga-option-label">${opcion}</span>
                        </label>
                    `;
                });

                preguntaDiv.innerHTML = `
                    <div class="carga-question-text">
                        <span class="carga-question-number">${contador}</span>
                        ${preguntaTexto}
                    </div>
                    <div class="carga-options">
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

        const preguntas = dimensionesContainer.querySelectorAll('.carga-question');

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

        const opcionesDeLaPregunta = event.target.closest('.carga-question').querySelectorAll('.carga-option');

        opcionesDeLaPregunta.forEach(o => o.classList.remove('selected'));

        event.target.closest('.carga-option').classList.add('selected');

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

        const secuencias = {
            baja: [523, 659],
            moderada: [440],
            alta: [392, 329],
            critica: [349, 294],
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

        let porcentaje = ((puntaje - 18) / (90 - 18)) * 100;

        porcentaje = Math.max(0, Math.min(100, porcentaje));

        const pisos  = { baja: 0,  moderada: 26, alta: 52, critica: 78 };
        const techos = { baja: 25, moderada: 51, alta: 77, critica: 100 };

        porcentaje = Math.max(porcentaje, pisos[nivel] ?? 0);
        porcentaje = Math.min(porcentaje, techos[nivel] ?? 100);

        return porcentaje;

    }

    function animarTermometro(nivel, puntaje, alTerminar) {

        const mask    = document.getElementById('thermoMask');
        const bulbo   = document.getElementById('thermoBulb');
        const caption = document.getElementById('thermoCaption');
        const marks   = document.querySelectorAll('.carga-thermo-marks .mark');

        const objetivo = calcularPorcentaje(nivel, puntaje);

        const colores = {
            baja: '#198754',
            moderada: '#f59e0b',
            alta: '#ea580c',
            critica: '#dc2626',
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

            const primeraSinResponder = dimensionesContainer.querySelector('.carga-question:not(.answered)');

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

        document.querySelectorAll('.carga-option.selected').forEach(o => o.classList.remove('selected'));

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

        document.querySelectorAll('.carga-thermo-marks .mark').forEach(m => {
            m.classList.remove('reached');
        });

        const resultCard = document.getElementById('resultCard');
        resultCard.className = 'carga-result-card nivel-' + resultado.color;

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
            (resultado.nivel === 'alta' || resultado.nivel === 'critica') ? 'block' : 'none';

        stepPreguntas.style.display = 'none';
        stepResultado.style.display = 'block';

        progressFill.style.width = '100%';

        window.scrollTo({ top: 0, behavior: 'smooth' });

        animarTermometro(resultado.nivel, resultado.puntaje, () => {
            details.classList.add('visible');
        });

    }

});