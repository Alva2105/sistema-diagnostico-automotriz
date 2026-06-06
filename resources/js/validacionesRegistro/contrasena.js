//! VALIDACIÓN PARA CONTRASEÑA
document.addEventListener("DOMContentLoaded", function () {
    const inputContrasena = document.getElementById("contrasena");
    const togglePassword = document.getElementById("togglePassword");
    const mensajeContrasena = document.getElementById("mensajeContrasena");
    const ContraError = document.getElementById("ContraError");
    const ContraCheck = document.getElementById("ContraCheck");
    const iconoOjo = document.getElementById("ojito1");

    if (!inputContrasena) return;

    function validarContrasena() {
        const valor = inputContrasena.value;

        //! Patrón: 9-10 caracteres, mínimo una mayúscula, una minúscula, un número y un símbolo
        const patron = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{9,10}$/;

        if (valor === "") {
            mensajeContrasena.textContent = "";
            mensajeContrasena.classList.remove("mostrar");
            inputContrasena.classList.remove("input-error", "input-ok");
            ContraError?.classList.remove("mostrar");
            ContraCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patron.test(valor)) {
            mensajeContrasena.textContent =
                "La contraseña debe tener entre 9 y 10 caracteres, incluir mayúsculas, minúsculas, números y símbolos.";
            mensajeContrasena.classList.add("mostrar");
            inputContrasena.classList.add("input-error");
            inputContrasena.classList.remove("input-ok");
            ContraError?.classList.add("mostrar");
            ContraCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeContrasena.textContent = "";
            mensajeContrasena.classList.remove("mostrar");
            inputContrasena.classList.remove("input-error");
            inputContrasena.classList.add("input-ok");
            ContraError?.classList.remove("mostrar");
            ContraCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarContrasena = validarContrasena;

    // Listener en tiempo real
    inputContrasena.addEventListener("input", validarContrasena);

    // Validación al enviar el formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarContrasena()) e.preventDefault();
        });
    }

    // Mostrar / ocultar contraseña
    if (togglePassword && iconoOjo) {
        togglePassword.addEventListener("click", function () {
            const tipo = inputContrasena.getAttribute("type") === "password" ? "text" : "password";
            inputContrasena.setAttribute("type", tipo);

            iconoOjo.src = tipo === "text"
                ? "assets/img/icons/ojo_abierto.png"
                : "assets/img/icons/ojo_cerrado.png";
        });
    }
});