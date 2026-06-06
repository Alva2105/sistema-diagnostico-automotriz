//! VALIDACIÓN PARA APELLIDO MATERNO
document.addEventListener("DOMContentLoaded", function () {
    const inputApMaterno = document.getElementById("apmaterno");
    const mensajeApMaterno = document.getElementById("mensajeApMaterno");
    const ApMaternoError = document.getElementById("ApMaternoError");
    const ApMaternoCheck = document.getElementById("ApMaternoCheck");

    if (!inputApMaterno) return;

    const patronUnaPalabra = /^[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+$/;

    function validarApellidoMaterno() {
        const valor = inputApMaterno.value.trim();

        if (valor === "") {
            mensajeApMaterno.textContent = "";
            mensajeApMaterno.classList.remove("mostrar");
            inputApMaterno.classList.remove("input-error", "input-ok");
            ApMaternoError?.classList.remove("mostrar");
            ApMaternoCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patronUnaPalabra.test(valor)) {
            mensajeApMaterno.textContent = "Debe ser una sola palabra que inicie con mayúscula seguida de minúsculas, sin números ni símbolos.";
            mensajeApMaterno.classList.add("mostrar");
            inputApMaterno.classList.add("input-error");
            inputApMaterno.classList.remove("input-ok");
            ApMaternoError?.classList.add("mostrar");
            ApMaternoCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeApMaterno.textContent = "";
            mensajeApMaterno.classList.remove("mostrar");
            inputApMaterno.classList.remove("input-error");
            inputApMaterno.classList.add("input-ok");
            ApMaternoError?.classList.remove("mostrar");
            ApMaternoCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarApellidoMaterno = validarApellidoMaterno;

    // Listener en tiempo real
    inputApMaterno.addEventListener("input", validarApellidoMaterno);

    // Validación al enviar
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarApellidoMaterno()) e.preventDefault();
        });
    }
});
