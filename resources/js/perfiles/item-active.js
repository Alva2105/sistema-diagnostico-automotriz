document.querySelectorAll('.menu__item').forEach(item => {
    item.addEventListener('click', function () {

        // Quitar activo de todos
        document.querySelectorAll('.menu__item')
            .forEach(i => i.classList.remove('menu__item--active'));

        // Activar el clicado
        this.classList.add('menu__item--active');
    });
});