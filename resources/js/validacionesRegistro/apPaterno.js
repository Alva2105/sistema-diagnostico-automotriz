//! VALIDACIÓN PARA APELLIDO PATERNO
document.addEventListener("DOMContentLoaded", function () {
    const inputApPaterno = document.getElementById("appaterno");
    const mensajeApPaterno = document.getElementById("mensajeApPaterno");
    const ApPaternoError = document.getElementById("ApPaternoError");
    const ApPaternoCheck = document.getElementById("ApPaternoCheck");

    if (!inputApPaterno) return;

    //! Permite 1 o 2 palabras, con mayúscula inicial y letras minúsculas
    const patronDosPalabras = /^([A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)?$/;

    function validarApellidoPaterno() {
        const valor = inputApPaterno.value.trim();

        if (valor === "") {
            mensajeApPaterno.textContent = "";
            mensajeApPaterno.classList.remove("mostrar");
            inputApPaterno.classList.remove("input-error", "input-ok");
            ApPaternoError?.classList.remove("mostrar");
            ApPaternoCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patronDosPalabras.test(valor)) {
            mensajeApPaterno.textContent = "Debe contener una o dos palabras, cada una iniciando con mayúscula y sin símbolos o números.";
            mensajeApPaterno.classList.add("mostrar");
            inputApPaterno.classList.add("input-error");
            inputApPaterno.classList.remove("input-ok");
            ApPaternoError?.classList.add("mostrar");
            ApPaternoCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeApPaterno.textContent = "";
            mensajeApPaterno.classList.remove("mostrar");
            inputApPaterno.classList.remove("input-error");
            inputApPaterno.classList.add("input-ok");
            ApPaternoError?.classList.remove("mostrar");
            ApPaternoCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarApellidoPaterno = validarApellidoPaterno;

    // Listener en tiempo real
    inputApPaterno.addEventListener("input", validarApellidoPaterno);

    // Validación al enviar el formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarApellidoPaterno()) e.preventDefault();
        });
    }
});