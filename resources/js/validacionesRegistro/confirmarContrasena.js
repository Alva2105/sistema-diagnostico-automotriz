//! VALIDACIÓN PARA CONFIRMACIÓN DE CONTRASEÑA
document.addEventListener("DOMContentLoaded", function () {
    const inputContrasena = document.getElementById("contrasena");
    const inputConfirmar = document.getElementById("confirmarContrasena");

    const mensajeConfirmar = document.getElementById("mensajeConfirmar");
    const ConfirmError = document.getElementById("ConfirmError");
    const ConfirmCheck = document.getElementById("ConfirmCheck");

    const toggleConfirmar = document.getElementById("toggleConfirmar");
    const iconoOjoConfirmar = document.getElementById("ojitoConfirmar");

    if (!inputConfirmar || !inputContrasena) return;

    function validarConfirmacion() {
        const contrasena = inputContrasena.value;
        const confirmacion = inputConfirmar.value;

        if (confirmacion === "") {
            mensajeConfirmar.textContent = "";
            mensajeConfirmar.classList.remove("mostrar");
            inputConfirmar.classList.remove("input-error", "input-ok");
            ConfirmError?.classList.remove("mostrar");
            ConfirmCheck?.classList.remove("mostrar");
            return false;
        }

        if (contrasena !== confirmacion) {
            mensajeConfirmar.textContent = "Verificar que las contraseñas ingresadas coincidan: caracteres, símbolos y números.";
            mensajeConfirmar.classList.add("mostrar");
            inputConfirmar.classList.add("input-error");
            inputConfirmar.classList.remove("input-ok");
            ConfirmError?.classList.add("mostrar");
            ConfirmCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeConfirmar.textContent = "";
            mensajeConfirmar.classList.remove("mostrar");
            inputConfirmar.classList.remove("input-error");
            inputConfirmar.classList.add("input-ok");
            ConfirmError?.classList.remove("mostrar");
            ConfirmCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarConfirmacion = validarConfirmacion;

    // Listener en tiempo real
    inputConfirmar.addEventListener("input", validarConfirmacion);

    // Validación al enviar el formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarConfirmacion()) e.preventDefault();
        });
    }

    // Mostrar / ocultar contraseña
    if (toggleConfirmar && iconoOjoConfirmar) {
        toggleConfirmar.addEventListener("click", function () {
            const tipo = inputConfirmar.getAttribute("type") === "password" ? "text" : "password";
            inputConfirmar.setAttribute("type", tipo);

            iconoOjoConfirmar.src = tipo === "text"
                ? "assets/img/icons/ojo_abierto.png"
                : "assets/img/icons/ojo_cerrado.png";
        });
    }
});