document.addEventListener('DOMContentLoaded', () => {

    const data = window.tamizajeData;
    if (!data) return;

    const preguntasFlat = [];
    data.dimensiones.forEach((dim, dimIndex) => {
        dim.preguntas.forEach((texto, pIndex) => {
            preguntasFlat.push({ dimIndex, pIndex, texto });
        });
    });

    const totalPreguntas = preguntasFlat.length;
    const respuestas = new Array(totalPreguntas).fill(null);

    const stepIntro = document.getElementById('stepIntro');
    const stepPreguntas = document.getElementById('stepPreguntas');
    const stepResultado = document.getElementById('stepResultado');
    const progressFill = document.getElementById('tmzProgressFill');
    const progressText = document.getElementById('tmzProgressText');
    const dimensionesContainer = document.getElementById('dimensionesContainer');
    const consentIntroCheck = document.getElementById('consentIntroCheck');
    const btnContinuarIntro = document.getElementById('btnContinuarIntro');
    const btnVolverIntro = document.getElementById('btnVolverIntro');
    const btnEnviar = document.getElementById('btnEnviar');
    const tmzWarning = document.getElementById('tmzWarning');
    const btnSonido = document.getElementById('btnSonido');

    let sonidoActivo = true;

    // --- Sonido simple con Web Audio (sin archivos externos) ---
    function reproducirTono() {
        if (!sonidoActivo) return;
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.value = 660;
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
            osc.connect(gain).connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.6);
        } catch (e) { /* silencioso si el navegador bloquea audio */ }
    }

    btnSonido.addEventListener('click', () => {
        sonidoActivo = !sonidoActivo;
        btnSonido.classList.toggle('muted', !sonidoActivo);
        btnSonido.innerHTML = sonidoActivo
            ? '<i class="fa-solid fa-volume-high"></i>'
            : '<i class="fa-solid fa-volume-xmark"></i>';
    });

    // --- PASO 0 ---
    consentIntroCheck.addEventListener('change', () => {
        btnContinuarIntro.disabled = !consentIntroCheck.checked;
    });

    btnContinuarIntro.addEventListener('click', () => {
        stepIntro.style.display = 'none';
        stepPreguntas.style.display = 'block';
        progressFill.style.width = '66%';
        renderPreguntas();
    });

    btnVolverIntro.addEventListener('click', () => {
        stepPreguntas.style.display = 'none';
        stepIntro.style.display = 'block';
        progressFill.style.width = '33%';
    });

    // --- PASO 1: renderizar dimensiones y preguntas ---
    function renderPreguntas() {
        dimensionesContainer.innerHTML = '';

        data.dimensiones.forEach((dim, dimIndex) => {
            const dimDiv = document.createElement('div');
            dimDiv.className = 'tmz-dimension';

            let html = `<h3>${dim.titulo}</h3>`;
            if (dim.descripcion) {
                html += `<p class="tmz-dimension-desc">${dim.descripcion}</p>`;
            }

            dim.preguntas.forEach((texto, pIndex) => {
                const flatIndex = preguntasFlat.findIndex(p => p.dimIndex === dimIndex && p.pIndex === pIndex);
                const numeroVisible = flatIndex + 1;

                html += `<div class="tmz-question" data-flat-index="${flatIndex}">
                    <div class="tmz-question-head">
                        <span class="tmz-question-num">${numeroVisible}</span>
                        <p>${texto}</p>
                    </div>
                    <div class="tmz-options">`;

                data.opciones.forEach((opcion, valorIndex) => {
                    const valor = valorIndex + 1;
                    html += `<label class="tmz-option" data-valor="${valor}">
                        <span class="tmz-option-radio"></span>
                        <span class="tmz-option-label">${opcion}</span>
                    </label>`;
                });

                html += `</div></div>`;
            });

            dimDiv.innerHTML = html;
            dimensionesContainer.appendChild(dimDiv);
        });

        // Listeners de selección
        document.querySelectorAll('.tmz-question').forEach(questionDiv => {
            const flatIndex = parseInt(questionDiv.dataset.flatIndex, 10);

            questionDiv.querySelectorAll('.tmz-option').forEach(optionDiv => {
                optionDiv.addEventListener('click', () => {
                    questionDiv.querySelectorAll('.tmz-option').forEach(o => o.classList.remove('selected'));
                    optionDiv.classList.add('selected');
                    respuestas[flatIndex] = parseInt(optionDiv.dataset.valor, 10);
                    actualizarProgreso();
                });
            });
        });

        actualizarProgreso();
    }

    function actualizarProgreso() {
        const respondidas = respuestas.filter(r => r !== null).length;
        progressText.textContent = `${respondidas} de ${totalPreguntas} respondidas`;
    }

    // --- Enviar respuestas ---
    btnEnviar.addEventListener('click', () => {
        const respondidas = respuestas.filter(r => r !== null).length;

        if (respondidas < totalPreguntas) {
            tmzWarning.classList.add('visible');
            return;
        }
        tmzWarning.classList.remove('visible');

        btnEnviar.disabled = true;

        fetch(data.evaluarUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data.csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ respuestas }),
        })
            .then(res => res.json())
            .then(mostrarResultado)
            .catch(() => {
                alert('Ocurrió un error al enviar tus respuestas. Intenta de nuevo.');
                btnEnviar.disabled = false;
            });
    });

    // --- Termómetro: dibuja las marcas de nivel (una vez, al cargar) ---
    function dibujarMarcas() {
        const marksContainer = document.getElementById('thermoMarks');
        if (!data.niveles || !data.niveles.length) return;

        marksContainer.innerHTML = data.niveles.map(n =>
            `<span class="mark">${n.etiqueta}</span>`
        ).join('');
    }
    dibujarMarcas();

    // --- Resultado con animación de termómetro ---
    function mostrarResultado(resultado) {
        stepPreguntas.style.display = 'none';
        stepResultado.style.display = 'block';
        progressFill.style.width = '100%';

        const thermoMask = document.getElementById('thermoMask');
        const thermoBulb = document.getElementById('thermoBulb');
        const thermoCaption = document.getElementById('thermoCaption');
        const resultDetails = document.getElementById('resultDetails');
        const resultCard = document.getElementById('resultCard');

        const porcentaje = Math.max(0, Math.min(100, resultado.porcentaje ?? 0));

        // Arranca en 0 y limpia clases de una ejecución anterior (por si repiten el ejercicio)
        thermoMask.style.height = '0%';
        thermoMask.className = 'tmz-thermo-mask';
        thermoBulb.className = 'tmz-thermo-bulb';
        resultCard.className = 'tmz-result-card';
        resultDetails.classList.remove('visible');

        setTimeout(() => {
            thermoMask.style.height = porcentaje + '%';
            thermoMask.classList.add(resultado.color);
            thermoBulb.classList.add(resultado.color);
            resultCard.classList.add('borde-' + resultado.color);
        }, 150);

        setTimeout(() => {
            thermoCaption.textContent = 'Resultado calculado';
            reproducirTono();

            const badge = document.getElementById('resultBadge');
            badge.textContent = resultado.etiqueta.toUpperCase();
            badge.className = 'tmz-result-badge ' + resultado.color;

            document.getElementById('resultTitulo').textContent = resultado.etiqueta;
            document.getElementById('resultTexto').textContent = resultado.resultado;
            document.getElementById('resultReflexiona').textContent = resultado.reflexiona;

            const lista = document.getElementById('resultRecomendaciones');
            lista.innerHTML = '';
            resultado.recomendaciones.forEach(rec => {
                const li = document.createElement('li');
                li.textContent = rec;
                lista.appendChild(li);
            });

            resultDetails.classList.add('visible');
        }, 1600);
    }

    document.getElementById('btnRepetir').addEventListener('click', () => {
        window.location.reload();
    });

});
