document.addEventListener('DOMContentLoaded', () => {

    const tabs = document.querySelectorAll('.login-tab');
    const indicator = document.getElementById('tabsIndicator');
    const panelEstudiante = document.getElementById('panelEstudiante');
    const panelAdministrador = document.getElementById('panelAdministrador');

    function activarTab(nombre, animar = true) {

        tabs.forEach(tab => {
            tab.classList.toggle('active', tab.dataset.tab === nombre);
        });

        const tabActivo = [...tabs].find(t => t.dataset.tab === nombre);

        if (tabActivo && indicator) {
            indicator.style.width = tabActivo.offsetWidth + 'px';
            indicator.style.left = tabActivo.offsetLeft + 'px';
        }

        const mostrarEstudiante = nombre === 'estudiante';

        if (animar) {

            const saliente = mostrarEstudiante ? panelAdministrador : panelEstudiante;
            const entrante = mostrarEstudiante ? panelEstudiante : panelAdministrador;

            saliente.classList.add('leaving');

            setTimeout(() => {

                saliente.classList.remove('active', 'leaving');
                entrante.classList.add('active');

            }, 200);

        } else {

            panelEstudiante.classList.toggle('active', mostrarEstudiante);
            panelAdministrador.classList.toggle('active', !mostrarEstudiante);

        }

    }

    tabs.forEach(tab => {

        tab.addEventListener('click', () => {
            activarTab(tab.dataset.tab);
        });

    });

    // Posiciona el indicador al cargar, y respeta si el servidor
    // indicó qué pestaña debía quedar activa (por ejemplo, tras un error)
    window.addEventListener('load', () => {
        activarTab(window.loginActiveTab || 'estudiante', false);
    });


    /*
    =========================================================
    MOSTRAR / OCULTAR CONTRASEÑA
    =========================================================
    */

    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {

        togglePassword.addEventListener('click', () => {

            const esTexto = passwordInput.type === 'text';

            passwordInput.type = esTexto ? 'password' : 'text';

            togglePassword.innerHTML = esTexto
                ? '<i class="fa-solid fa-eye"></i>'
                : '<i class="fa-solid fa-eye-slash"></i>';

        });

    }


    /*
    =========================================================
    ESTADO DE CARGA AL ENVIAR
    =========================================================
    */

    document.querySelectorAll('.login-form-estudiante, .login-form-admin').forEach(form => {

        form.addEventListener('submit', () => {

            const btn = form.querySelector('.login-submit-btn');

            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span>Verificando...</span> <i class="fa-solid fa-spinner fa-spin"></i>';
            }

        });

    });

});