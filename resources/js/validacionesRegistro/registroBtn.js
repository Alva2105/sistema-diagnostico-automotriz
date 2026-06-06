document.addEventListener("DOMContentLoaded", function () {
    const btnRegistrar = document.getElementById("btnRegistrar");
    const formulario = document.getElementById("formRegistro");
    if (!btnRegistrar || !formulario) return;

    btnRegistrar.disabled = true;

    // Función que verifica todas las validaciones
    function todasLasValidaciones() {
        return (
            window.validarNombre?.() &&
            window.validarApellidoPaterno?.() &&
            window.validarApellidoMaterno?.() &&
            window.validarTelefono?.() &&
            window.validarCorreo?.() &&
            window.validarCI?.() &&
            window.validarContrasena?.() &&
            window.validarConfirmacion?.() &&
            window.validarDireccion?.() &&
            window.validarFecha?.()
        );
    }

    // Validar cada input en tiempo real
    formulario.querySelectorAll("input").forEach((input) => {
        input.addEventListener("input", () => {
            btnRegistrar.disabled = !todasLasValidaciones();
        });
    });

    // Bloquear envío si alguna validación falla
    formulario.addEventListener("submit", function (event) {
        if (!todasLasValidaciones()) {
            event.preventDefault();
            btnRegistrar.disabled = true;
        }
    });
});