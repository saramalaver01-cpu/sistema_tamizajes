/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/login.js ***!
  \*******************************/
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
document.addEventListener('DOMContentLoaded', function () {
  var tabs = document.querySelectorAll('.login-tab');
  var indicator = document.getElementById('tabsIndicator');
  var panelEstudiante = document.getElementById('panelEstudiante');
  var panelAdministrador = document.getElementById('panelAdministrador');
  function activarTab(nombre) {
    var animar = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    tabs.forEach(function (tab) {
      tab.classList.toggle('active', tab.dataset.tab === nombre);
    });
    var tabActivo = _toConsumableArray(tabs).find(function (t) {
      return t.dataset.tab === nombre;
    });
    if (tabActivo && indicator) {
      indicator.style.width = tabActivo.offsetWidth + 'px';
      indicator.style.left = tabActivo.offsetLeft + 'px';
    }
    var mostrarEstudiante = nombre === 'estudiante';
    if (animar) {
      var saliente = mostrarEstudiante ? panelAdministrador : panelEstudiante;
      var entrante = mostrarEstudiante ? panelEstudiante : panelAdministrador;
      saliente.classList.add('leaving');
      setTimeout(function () {
        saliente.classList.remove('active', 'leaving');
        entrante.classList.add('active');
      }, 200);
    } else {
      panelEstudiante.classList.toggle('active', mostrarEstudiante);
      panelAdministrador.classList.toggle('active', !mostrarEstudiante);
    }
  }
  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      activarTab(tab.dataset.tab);
    });
  });

  // Posiciona el indicador al cargar, y respeta si el servidor
  // indicó qué pestaña debía quedar activa (por ejemplo, tras un error)
  window.addEventListener('load', function () {
    activarTab(window.loginActiveTab || 'estudiante', false);
  });

  /*
  =========================================================
  MOSTRAR / OCULTAR CONTRASEÑA
  =========================================================
  */

  var togglePassword = document.getElementById('togglePassword');
  var passwordInput = document.getElementById('password');
  if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function () {
      var esTexto = passwordInput.type === 'text';
      passwordInput.type = esTexto ? 'password' : 'text';
      togglePassword.innerHTML = esTexto ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
    });
  }

  /*
  =========================================================
  ESTADO DE CARGA AL ENVIAR
  =========================================================
  */

  document.querySelectorAll('.login-form-estudiante, .login-form-admin').forEach(function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('.login-submit-btn');
      if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span>Verificando...</span> <i class="fa-solid fa-spinner fa-spin"></i>';
      }
    });
  });
});
/******/ })()
;