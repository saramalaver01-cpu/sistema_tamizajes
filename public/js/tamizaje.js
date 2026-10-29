/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/tamizaje.js ***!
  \**********************************/
document.addEventListener('DOMContentLoaded', function () {
  var data = window.tamizajeData;
  if (!data) return;
  var preguntasFlat = [];
  data.dimensiones.forEach(function (dim, dimIndex) {
    dim.preguntas.forEach(function (texto, pIndex) {
      preguntasFlat.push({
        dimIndex: dimIndex,
        pIndex: pIndex,
        texto: texto
      });
    });
  });
  var totalPreguntas = preguntasFlat.length;
  var respuestas = new Array(totalPreguntas).fill(null);
  var stepIntro = document.getElementById('stepIntro');
  var stepPreguntas = document.getElementById('stepPreguntas');
  var stepResultado = document.getElementById('stepResultado');
  var progressFill = document.getElementById('tmzProgressFill');
  var progressText = document.getElementById('tmzProgressText');
  var dimensionesContainer = document.getElementById('dimensionesContainer');
  var consentIntroCheck = document.getElementById('consentIntroCheck');
  var btnContinuarIntro = document.getElementById('btnContinuarIntro');
  var btnVolverIntro = document.getElementById('btnVolverIntro');
  var btnEnviar = document.getElementById('btnEnviar');
  var tmzWarning = document.getElementById('tmzWarning');
  var btnSonido = document.getElementById('btnSonido');
  var sonidoActivo = true;

  // --- Sonido simple con Web Audio (sin archivos externos) ---
  function reproducirTono() {
    if (!sonidoActivo) return;
    try {
      var ctx = new (window.AudioContext || window.webkitAudioContext)();
      var osc = ctx.createOscillator();
      var gain = ctx.createGain();
      osc.type = 'sine';
      osc.frequency.value = 660;
      gain.gain.setValueAtTime(0.08, ctx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
      osc.connect(gain).connect(ctx.destination);
      osc.start();
      osc.stop(ctx.currentTime + 0.6);
    } catch (e) {/* silencioso si el navegador bloquea audio */}
  }
  btnSonido.addEventListener('click', function () {
    sonidoActivo = !sonidoActivo;
    btnSonido.classList.toggle('muted', !sonidoActivo);
    btnSonido.innerHTML = sonidoActivo ? '<i class="fa-solid fa-volume-high"></i>' : '<i class="fa-solid fa-volume-xmark"></i>';
  });

  // --- PASO 0 ---
  consentIntroCheck.addEventListener('change', function () {
    btnContinuarIntro.disabled = !consentIntroCheck.checked;
  });
  btnContinuarIntro.addEventListener('click', function () {
    stepIntro.style.display = 'none';
    stepPreguntas.style.display = 'block';
    progressFill.style.width = '66%';
    renderPreguntas();
  });
  btnVolverIntro.addEventListener('click', function () {
    stepPreguntas.style.display = 'none';
    stepIntro.style.display = 'block';
    progressFill.style.width = '33%';
  });

  // --- PASO 1: renderizar dimensiones y preguntas ---
  function renderPreguntas() {
    dimensionesContainer.innerHTML = '';
    data.dimensiones.forEach(function (dim, dimIndex) {
      var dimDiv = document.createElement('div');
      dimDiv.className = 'tmz-dimension';
      var html = "<h3>".concat(dim.titulo, "</h3>");
      if (dim.descripcion) {
        html += "<p class=\"tmz-dimension-desc\">".concat(dim.descripcion, "</p>");
      }
      dim.preguntas.forEach(function (texto, pIndex) {
        var flatIndex = preguntasFlat.findIndex(function (p) {
          return p.dimIndex === dimIndex && p.pIndex === pIndex;
        });
        var numeroVisible = flatIndex + 1;
        html += "<div class=\"tmz-question\" data-flat-index=\"".concat(flatIndex, "\">\n                    <div class=\"tmz-question-head\">\n                        <span class=\"tmz-question-num\">").concat(numeroVisible, "</span>\n                        <p>").concat(texto, "</p>\n                    </div>\n                    <div class=\"tmz-options\">");
        data.opciones.forEach(function (opcion, valorIndex) {
          var valor = valorIndex + 1;
          html += "<label class=\"tmz-option\" data-valor=\"".concat(valor, "\">\n                        <span class=\"tmz-option-radio\"></span>\n                        <span class=\"tmz-option-label\">").concat(opcion, "</span>\n                    </label>");
        });
        html += "</div></div>";
      });
      dimDiv.innerHTML = html;
      dimensionesContainer.appendChild(dimDiv);
    });

    // Listeners de selección
    document.querySelectorAll('.tmz-question').forEach(function (questionDiv) {
      var flatIndex = parseInt(questionDiv.dataset.flatIndex, 10);
      questionDiv.querySelectorAll('.tmz-option').forEach(function (optionDiv) {
        optionDiv.addEventListener('click', function () {
          questionDiv.querySelectorAll('.tmz-option').forEach(function (o) {
            return o.classList.remove('selected');
          });
          optionDiv.classList.add('selected');
          respuestas[flatIndex] = parseInt(optionDiv.dataset.valor, 10);
          actualizarProgreso();
        });
      });
    });
    actualizarProgreso();
  }
  function actualizarProgreso() {
    var respondidas = respuestas.filter(function (r) {
      return r !== null;
    }).length;
    progressText.textContent = "".concat(respondidas, " de ").concat(totalPreguntas, " respondidas");
  }

  // --- Enviar respuestas ---
  btnEnviar.addEventListener('click', function () {
    var respondidas = respuestas.filter(function (r) {
      return r !== null;
    }).length;
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
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        respuestas: respuestas
      })
    }).then(function (res) {
      return res.json();
    }).then(mostrarResultado)["catch"](function () {
      alert('Ocurrió un error al enviar tus respuestas. Intenta de nuevo.');
      btnEnviar.disabled = false;
    });
  });

  // --- Termómetro: dibuja las marcas de nivel (una vez, al cargar) ---
  function dibujarMarcas() {
    var marksContainer = document.getElementById('thermoMarks');
    if (!data.niveles || !data.niveles.length) return;
    marksContainer.innerHTML = data.niveles.map(function (n) {
      return "<span class=\"mark\">".concat(n.etiqueta, "</span>");
    }).join('');
  }
  dibujarMarcas();

  // --- Resultado con animación de termómetro ---
  function mostrarResultado(resultado) {
    var _resultado$porcentaje;
    stepPreguntas.style.display = 'none';
    stepResultado.style.display = 'block';
    progressFill.style.width = '100%';
    var thermoMask = document.getElementById('thermoMask');
    var thermoBulb = document.getElementById('thermoBulb');
    var thermoCaption = document.getElementById('thermoCaption');
    var resultDetails = document.getElementById('resultDetails');
    var resultCard = document.getElementById('resultCard');
    var porcentaje = Math.max(0, Math.min(100, (_resultado$porcentaje = resultado.porcentaje) !== null && _resultado$porcentaje !== void 0 ? _resultado$porcentaje : 0));

    // Arranca en 0 y limpia clases de una ejecución anterior (por si repiten el ejercicio)
    thermoMask.style.height = '0%';
    thermoMask.className = 'tmz-thermo-mask';
    thermoBulb.className = 'tmz-thermo-bulb';
    resultCard.className = 'tmz-result-card';
    resultDetails.classList.remove('visible');
    setTimeout(function () {
      thermoMask.style.height = porcentaje + '%';
      thermoMask.classList.add(resultado.color);
      thermoBulb.classList.add(resultado.color);
      resultCard.classList.add('borde-' + resultado.color);
    }, 150);
    setTimeout(function () {
      thermoCaption.textContent = 'Resultado calculado';
      reproducirTono();
      var badge = document.getElementById('resultBadge');
      badge.textContent = resultado.etiqueta.toUpperCase();
      badge.className = 'tmz-result-badge ' + resultado.color;
      document.getElementById('resultTitulo').textContent = resultado.etiqueta;
      document.getElementById('resultTexto').textContent = resultado.resultado;
      document.getElementById('resultReflexiona').textContent = resultado.reflexiona;
      var lista = document.getElementById('resultRecomendaciones');
      lista.innerHTML = '';
      resultado.recomendaciones.forEach(function (rec) {
        var li = document.createElement('li');
        li.textContent = rec;
        lista.appendChild(li);
      });
      resultDetails.classList.add('visible');
    }, 1600);
  }
  document.getElementById('btnRepetir').addEventListener('click', function () {
    window.location.reload();
  });
});
/******/ })()
;