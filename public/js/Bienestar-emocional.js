/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./resources/js/Bienestar-emocional.js ***!
  \*********************************************/
function _regenerator() { /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/babel/babel/blob/main/packages/babel-helpers/LICENSE */ var e, t, r = "function" == typeof Symbol ? Symbol : {}, n = r.iterator || "@@iterator", o = r.toStringTag || "@@toStringTag"; function i(r, n, o, i) { var c = n && n.prototype instanceof Generator ? n : Generator, u = Object.create(c.prototype); return _regeneratorDefine2(u, "_invoke", function (r, n, o) { var i, c, u, f = 0, p = o || [], y = !1, G = { p: 0, n: 0, v: e, a: d, f: d.bind(e, 4), d: function d(t, r) { return i = t, c = 0, u = e, G.n = r, a; } }; function d(r, n) { for (c = r, u = n, t = 0; !y && f && !o && t < p.length; t++) { var o, i = p[t], d = G.p, l = i[2]; r > 3 ? (o = l === n) && (u = i[(c = i[4]) ? 5 : (c = 3, 3)], i[4] = i[5] = e) : i[0] <= d && ((o = r < 2 && d < i[1]) ? (c = 0, G.v = n, G.n = i[1]) : d < l && (o = r < 3 || i[0] > n || n > l) && (i[4] = r, i[5] = n, G.n = l, c = 0)); } if (o || r > 1) return a; throw y = !0, n; } return function (o, p, l) { if (f > 1) throw TypeError("Generator is already running"); for (y && 1 === p && d(p, l), c = p, u = l; (t = c < 2 ? e : u) || !y;) { i || (c ? c < 3 ? (c > 1 && (G.n = -1), d(c, u)) : G.n = u : G.v = u); try { if (f = 2, i) { if (c || (o = "next"), t = i[o]) { if (!(t = t.call(i, u))) throw TypeError("iterator result is not an object"); if (!t.done) return t; u = t.value, c < 2 && (c = 0); } else 1 === c && (t = i["return"]) && t.call(i), c < 2 && (u = TypeError("The iterator does not provide a '" + o + "' method"), c = 1); i = e; } else if ((t = (y = G.n < 0) ? u : r.call(n, G)) !== a) break; } catch (t) { i = e, c = 1, u = t; } finally { f = 1; } } return { value: t, done: y }; }; }(r, o, i), !0), u; } var a = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} t = Object.getPrototypeOf; var c = [][n] ? t(t([][n]())) : (_regeneratorDefine2(t = {}, n, function () { return this; }), t), u = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(c); function f(e) { return Object.setPrototypeOf ? Object.setPrototypeOf(e, GeneratorFunctionPrototype) : (e.__proto__ = GeneratorFunctionPrototype, _regeneratorDefine2(e, o, "GeneratorFunction")), e.prototype = Object.create(u), e; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, _regeneratorDefine2(u, "constructor", GeneratorFunctionPrototype), _regeneratorDefine2(GeneratorFunctionPrototype, "constructor", GeneratorFunction), GeneratorFunction.displayName = "GeneratorFunction", _regeneratorDefine2(GeneratorFunctionPrototype, o, "GeneratorFunction"), _regeneratorDefine2(u), _regeneratorDefine2(u, o, "Generator"), _regeneratorDefine2(u, n, function () { return this; }), _regeneratorDefine2(u, "toString", function () { return "[object Generator]"; }), (_regenerator = function _regenerator() { return { w: i, m: f }; })(); }
function _regeneratorDefine2(e, r, n, t) { var i = Object.defineProperty; try { i({}, "", {}); } catch (e) { i = 0; } _regeneratorDefine2 = function _regeneratorDefine(e, r, n, t) { function o(r, n) { _regeneratorDefine2(e, r, function (e) { return this._invoke(r, n, e); }); } r ? i ? i(e, r, { value: n, enumerable: !t, configurable: !t, writable: !t }) : e[r] = n : (o("next", 0), o("throw", 1), o("return", 2)); }, _regeneratorDefine2(e, r, n, t); }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
document.addEventListener('DOMContentLoaded', function () {
  var data = window.bienestarData;
  if (!data) return;
  var totalPreguntas = 0;

  /*
  =========================================================
  ELEMENTOS
  =========================================================
  */

  var stepIntro = document.getElementById('stepIntro');
  var stepPreguntas = document.getElementById('stepPreguntas');
  var stepResultado = document.getElementById('stepResultado');
  var consentIntroCheck = document.getElementById('consentIntroCheck');
  var btnContinuarIntro = document.getElementById('btnContinuarIntro');
  var btnVolverIntro = document.getElementById('btnVolverIntro');
  var dimensionesContainer = document.getElementById('dimensionesContainer');
  var progressFill = document.getElementById('bienestarProgressFill');
  var progressText = document.getElementById('bienestarProgressText');
  var warning = document.getElementById('bienestarWarning');
  var btnEnviar = document.getElementById('btnEnviar');
  var btnRepetir = document.getElementById('btnRepetir');

  /*
  =========================================================
  PASO 0: INTRODUCCIÓN
  =========================================================
  */

  consentIntroCheck.addEventListener('change', function () {
    btnContinuarIntro.disabled = !consentIntroCheck.checked;
  });
  btnContinuarIntro.addEventListener('click', function () {
    renderPreguntas();
    stepIntro.style.display = 'none';
    stepPreguntas.style.display = 'block';
    progressFill.style.width = '66%';
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  btnVolverIntro.addEventListener('click', function () {
    stepPreguntas.style.display = 'none';
    stepIntro.style.display = 'block';
    progressFill.style.width = '33%';
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  /*
  =========================================================
  RENDER DE PREGUNTAS
  =========================================================
  */

  function renderPreguntas() {
    if (dimensionesContainer.dataset.rendered === 'true') return;
    var contador = 0;
    data.dimensiones.forEach(function (dimension) {
      var seccion = document.createElement('div');
      seccion.className = 'bienestar-dimension';
      seccion.innerHTML = "\n                <h3>".concat(dimension.titulo, "</h3>\n                <p class=\"bienestar-dimension-desc\">").concat(dimension.descripcion, "</p>\n            ");
      dimension.preguntas.forEach(function (preguntaTexto) {
        contador++;
        var preguntaDiv = document.createElement('div');
        preguntaDiv.className = 'bienestar-question';
        preguntaDiv.dataset.index = contador - 1;
        var opcionesHtml = '';
        data.opciones.forEach(function (opcion, i) {
          opcionesHtml += "\n                        <label class=\"bienestar-option\">\n                            <input type=\"radio\" name=\"respuesta_".concat(contador - 1, "\" value=\"").concat(i + 1, "\">\n                            <span class=\"bienestar-radio-circle\"></span>\n                            <span class=\"bienestar-option-label\">").concat(opcion, "</span>\n                        </label>\n                    ");
        });
        preguntaDiv.innerHTML = "\n                    <div class=\"bienestar-question-text\">\n                        <span class=\"bienestar-question-number\">".concat(contador, "</span>\n                        ").concat(preguntaTexto, "\n                    </div>\n                    <div class=\"bienestar-options\">\n                        ").concat(opcionesHtml, "\n                    </div>\n                ");
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
    var preguntas = dimensionesContainer.querySelectorAll('.bienestar-question');
    var respondidas = 0;
    preguntas.forEach(function (pregunta) {
      var marcada = pregunta.querySelector('input[type="radio"]:checked');
      pregunta.classList.toggle('answered', !!marcada);
      if (marcada) respondidas++;
    });
    progressText.textContent = "".concat(respondidas, " de ").concat(totalPreguntas, " respondidas");
    return respondidas;
  }
  dimensionesContainer.addEventListener('change', function (event) {
    if (event.target.type !== 'radio') return;
    var opcionesDeLaPregunta = event.target.closest('.bienestar-question').querySelectorAll('.bienestar-option');
    opcionesDeLaPregunta.forEach(function (o) {
      return o.classList.remove('selected');
    });
    event.target.closest('.bienestar-option').classList.add('selected');
    actualizarProgreso();
    warning.classList.remove('show');
  });

  /*
  =========================================================
  SONIDO — tonos suaves
  =========================================================
  */

  var sonidoActivo = true;
  var audioCtx = null;
  var btnSonido = document.getElementById('btnSonido');
  if (btnSonido) {
    btnSonido.addEventListener('click', function () {
      sonidoActivo = !sonidoActivo;
      btnSonido.innerHTML = sonidoActivo ? '<i class="fa-solid fa-volume-high"></i>' : '<i class="fa-solid fa-volume-xmark"></i>';
    });
  }
  function getAudioCtx() {
    if (!sonidoActivo) return null;
    if (!audioCtx) {
      var AudioCtx = window.AudioContext || window.webkitAudioContext;
      audioCtx = new AudioCtx();
    }
    return audioCtx;
  }
  function tono(frecuencia) {
    var duracion = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0.18;
    var volumen = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 0.09;
    var retardo = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 0;
    var ctx = getAudioCtx();
    if (!ctx) return;
    var oscillator = ctx.createOscillator();
    var gain = ctx.createGain();
    oscillator.type = 'sine';
    oscillator.frequency.value = frecuencia;
    var inicio = ctx.currentTime + retardo;
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
    var secuencias = {
      fortalecido: [523, 659],
      estable: [493, 587],
      seguimiento: [440],
      atencion: [349, 294]
    };
    var notas = secuencias[nivel] || [440];
    notas.forEach(function (frecuencia, i) {
      tono(frecuencia, 0.5, 0.1, i * 0.3);
    });
  }

  /*
  =========================================================
  TERMÓMETRO ANIMADO
  =========================================================
  */

  function calcularPorcentaje(nivel, puntaje) {
    var _pisos$nivel, _techos$nivel;
    var porcentaje = (puntaje - 21) / (105 - 21) * 100;
    porcentaje = Math.max(0, Math.min(100, porcentaje));
    var pisos = {
      fortalecido: 0,
      estable: 26,
      seguimiento: 52,
      atencion: 78
    };
    var techos = {
      fortalecido: 25,
      estable: 51,
      seguimiento: 77,
      atencion: 100
    };
    porcentaje = Math.max(porcentaje, (_pisos$nivel = pisos[nivel]) !== null && _pisos$nivel !== void 0 ? _pisos$nivel : 0);
    porcentaje = Math.min(porcentaje, (_techos$nivel = techos[nivel]) !== null && _techos$nivel !== void 0 ? _techos$nivel : 100);
    return porcentaje;
  }
  function animarTermometro(nivel, puntaje, alTerminar) {
    var mask = document.getElementById('thermoMask');
    var bulbo = document.getElementById('thermoBulb');
    var caption = document.getElementById('thermoCaption');
    var marks = document.querySelectorAll('.bienestar-thermo-marks .mark');
    var objetivo = calcularPorcentaje(nivel, puntaje);
    var colores = {
      fortalecido: '#198754',
      estable: '#2563eb',
      seguimiento: '#f59e0b',
      atencion: '#ea580c'
    };
    bulbo.style.setProperty('--bulb-color', colores[nivel]);
    var duracion = 2200;
    var inicio = performance.now();
    var marcasDisparadas = new Set();
    function paso(ahora) {
      var transcurrido = ahora - inicio;
      var progreso = Math.min(transcurrido / duracion, 1);
      var progresoSuave = 1 - Math.pow(1 - progreso, 3);
      var actual = progresoSuave * objetivo;
      mask.style.height = 100 - actual + '%';
      marks.forEach(function (mark) {
        var valorMarca = parseFloat(mark.dataset.mark);
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

  btnEnviar.addEventListener('click', /*#__PURE__*/_asyncToGenerator(/*#__PURE__*/_regenerator().m(function _callee() {
    var respondidas, primeraSinResponder, respuestas, i, marcada, response, resultado, _t;
    return _regenerator().w(function (_context) {
      while (1) switch (_context.p = _context.n) {
        case 0:
          respondidas = actualizarProgreso();
          if (!(respondidas < totalPreguntas)) {
            _context.n = 1;
            break;
          }
          warning.classList.add('show');
          primeraSinResponder = dimensionesContainer.querySelector('.bienestar-question:not(.answered)');
          if (primeraSinResponder) {
            primeraSinResponder.scrollIntoView({
              behavior: 'smooth',
              block: 'center'
            });
          }
          return _context.a(2);
        case 1:
          respuestas = [];
          for (i = 0; i < totalPreguntas; i++) {
            marcada = dimensionesContainer.querySelector("input[name=\"respuesta_".concat(i, "\"]:checked"));
            respuestas.push(parseInt(marcada.value, 10));
          }
          btnEnviar.disabled = true;
          btnEnviar.innerHTML = 'Calculando... <i class="fa-solid fa-spinner fa-spin"></i>';
          _context.p = 2;
          _context.n = 3;
          return fetch(data.evaluarUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': data.csrf,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              respuestas: respuestas
            })
          });
        case 3:
          response = _context.v;
          _context.n = 4;
          return response.json();
        case 4:
          resultado = _context.v;
          mostrarResultado(resultado);
          _context.n = 6;
          break;
        case 5:
          _context.p = 5;
          _t = _context.v;
          alert('Ocurrió un error al procesar tu resultado. Intenta de nuevo.');
        case 6:
          _context.p = 6;
          btnEnviar.disabled = false;
          btnEnviar.innerHTML = 'Enviar respuestas <i class="fa-solid fa-arrow-right"></i>';
          return _context.f(6);
        case 7:
          return _context.a(2);
      }
    }, _callee, null, [[2, 5, 6, 7]]);
  })));
  btnRepetir.addEventListener('click', function () {
    document.querySelectorAll('#dimensionesContainer input[type="radio"]:checked').forEach(function (input) {
      input.checked = false;
    });
    document.querySelectorAll('.bienestar-option.selected').forEach(function (o) {
      return o.classList.remove('selected');
    });
    actualizarProgreso();
    stepResultado.style.display = 'none';
    stepIntro.style.display = 'block';
    consentIntroCheck.checked = false;
    btnContinuarIntro.disabled = true;
    progressFill.style.width = '33%';
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });
  function mostrarResultado(resultado) {
    var mask = document.getElementById('thermoMask');
    var bulbo = document.getElementById('thermoBulb');
    var details = document.getElementById('resultDetails');
    var caption = document.getElementById('thermoCaption');
    mask.style.height = '100%';
    bulbo.classList.remove('activo');
    details.classList.remove('visible');
    caption.textContent = 'Procesando tus respuestas...';
    document.querySelectorAll('.bienestar-thermo-marks .mark').forEach(function (m) {
      m.classList.remove('reached');
    });
    var resultCard = document.getElementById('resultCard');
    resultCard.className = 'bienestar-result-card nivel-' + resultado.color;
    document.getElementById('resultBadge').textContent = resultado.etiqueta;
    document.getElementById('resultTexto').textContent = resultado.resultado;
    document.getElementById('resultReflexiona').textContent = resultado.reflexiona;
    var lista = document.getElementById('resultRecomendaciones');
    lista.innerHTML = '';
    resultado.recomendaciones.forEach(function (rec) {
      var li = document.createElement('li');
      li.textContent = rec;
      lista.appendChild(li);
    });
    document.getElementById('resultRecursos').style.display = resultado.nivel === 'seguimiento' || resultado.nivel === 'atencion' ? 'block' : 'none';
    stepPreguntas.style.display = 'none';
    stepResultado.style.display = 'block';
    progressFill.style.width = '100%';
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
    animarTermometro(resultado.nivel, resultado.puntaje, function () {
      details.classList.add('visible');
    });
  }
});
/******/ })()
;