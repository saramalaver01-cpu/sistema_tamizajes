/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*******************************!*\
  !*** ./resources/js/admin.js ***!
  \*******************************/
document.addEventListener('DOMContentLoaded', function () {
  var sidebar = document.getElementById('adminSidebar');
  var toggle = document.getElementById('adminSidebarToggle');
  if (sidebar && toggle) {
    toggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
    document.addEventListener('click', function (event) {
      if (window.innerWidth <= 768 && sidebar.classList.contains('open') && !sidebar.contains(event.target) && !toggle.contains(event.target)) {
        sidebar.classList.remove('open');
      }
    });
  }
});
/******/ })()
;