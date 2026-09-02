document.addEventListener('DOMContentLoaded', () => {

    const menuBtn = document.getElementById('menuBtn');
    const navLinks = document.getElementById('navLinks');


    /*
    =========================================================
    MENÚ RESPONSIVE
    =========================================================
    */

    if (menuBtn && navLinks) {

        menuBtn.addEventListener('click', () => {

            navLinks.classList.toggle('active');

            const icon = menuBtn.querySelector('i');

            if (navLinks.classList.contains('active')) {

                icon.classList.remove('fa-bars');

                icon.classList.add('fa-xmark');

            } else {

                icon.classList.remove('fa-xmark');

                icon.classList.add('fa-bars');

            }

        });


        /*
        Cerrar menú al seleccionar una opción
        */

        navLinks.querySelectorAll('a').forEach(link => {

            link.addEventListener('click', () => {

                navLinks.classList.remove('active');

                const icon = menuBtn.querySelector('i');

                icon.classList.remove('fa-xmark');

                icon.classList.add('fa-bars');

            });

        });

    }


    /*
    =========================================================
    ANIMACIÓN AL HACER SCROLL
    =========================================================
    */

    const animatedElements = document.querySelectorAll(
        '.service-card, .module-card, .benefit-card'
    );


    const observer = new IntersectionObserver(
        (entries) => {

            entries.forEach(entry => {

               if (entry.isIntersecting) {

                    entry.target.classList.add('visible');

                    observer.unobserve(entry.target);

                }

            });

        },
        {
            threshold: 0.15
        }
    );


    animatedElements.forEach(element => {

        

        observer.observe(element);

    });


        /*
    =========================================================
    MENÚ DE USUARIO (dropdown)
    =========================================================
    */

    const userMenu = document.getElementById('userMenu');
    const userMenuTrigger = document.getElementById('userMenuTrigger');

    if (userMenu && userMenuTrigger) {

        userMenuTrigger.addEventListener('click', (event) => {

            event.stopPropagation();

            userMenu.classList.toggle('open');

        });

        document.addEventListener('click', (event) => {

            if (!userMenu.contains(event.target)) {
                userMenu.classList.remove('open');
            }

        });

    }

});