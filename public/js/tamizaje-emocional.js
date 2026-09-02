/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!********************************************!*\
  !*** ./resources/js/tamizaje-emocional.js ***!
  \********************************************/
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('tamizForm');
  if (!form) return;
  var questions = form.querySelectorAll('.tamiz-question');
  var totalQuestions = questions.length;
  var progressFill = document.getElementById('tamizProgressFill');
  var progressText = document.getElementById('tamizProgressText');
  var warning = document.getElementById('tamizIncompleteWarning');
  var submitBtn = document.getElementById('tamizSubmitBtn');

  /*
  =========================================================
  ACTUALIZAR PROGRESO
  =========================================================
  */

  function updateProgress() {
    var answered = 0;
    questions.forEach(function (question) {
      var checked = question.querySelector('input[type="radio"]:checked');
      question.classList.toggle('answered', !!checked);
      if (checked) answered++;
    });
    var percent = Math.round(answered / totalQuestions * 100);
    progressFill.style.width = percent + '%';
    progressText.textContent = "".concat(answered, " de ").concat(totalQuestions, " preguntas respondidas");
    return answered;
  }

  /*
  =========================================================
  MARCAR OPCIÓN SELECCIONADA (estilo visual del círculo)
  =========================================================
  */

  form.addEventListener('change', function (event) {
    if (event.target.type !== 'radio') return;
    var question = event.target.closest('.tamiz-question');
    question.querySelectorAll('.tamiz-option').forEach(function (option) {
      option.classList.remove('selected');
    });
    event.target.closest('.tamiz-option').classList.add('selected');
    updateProgress();
    warning.classList.remove('show');
  });

  /*
  =========================================================
  VALIDAR ANTES DE ENVIAR
  =========================================================
  */

  form.addEventListener('submit', function (event) {
    var answered = updateProgress();
    if (answered < totalQuestions) {
      event.preventDefault();
      warning.classList.add('show');
      var firstUnanswered = form.querySelector('.tamiz-question:not(.answered)');
      if (firstUnanswered) {
        firstUnanswered.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        firstUnanswered.classList.add('shake');
        setTimeout(function () {
          return firstUnanswered.classList.remove('shake');
        }, 500);
      }
    } else {
      submitBtn.disabled = true;
      submitBtn.innerHTML = 'Enviando... <i class="fa-solid fa-spinner fa-spin"></i>';
    }
  });
  updateProgress();
});
/******/ })()
;