document.addEventListener('DOMContentLoaded', () => {

    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('adminSidebarToggle');

    if (sidebar && toggle) {

        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        document.addEventListener('click', (event) => {

            if (window.innerWidth <= 768 &&
                sidebar.classList.contains('open') &&
                !sidebar.contains(event.target) &&
                !toggle.contains(event.target)) {

                sidebar.classList.remove('open');

            }

        });

    }

});