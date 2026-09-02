/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*************************************!*\
  !*** ./resources/js/admin-login.js ***!
  \*************************************/
document.addEventListener('DOMContentLoaded', function () {
  var togglePassword = document.getElementById('togglePassword');
  var passwordInput = document.getElementById('password');
  var form = document.getElementById('adminLoginForm');
  var submitBtn = document.getElementById('adminSubmitBtn');
  if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function () {
      var esTexto = passwordInput.type === 'text';
      passwordInput.type = esTexto ? 'password' : 'text';
      togglePassword.innerHTML = esTexto ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';
    });
  }
  if (form && submitBtn) {
    form.addEventListener('submit', function () {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span>Verificando...</span> <i class="fa-solid fa-spinner fa-spin"></i>';
    });
  }
});
/******/ })()
;