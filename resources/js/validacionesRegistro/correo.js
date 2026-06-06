//! VALIDACIÓN PARA CORREO ELECTRÓNICO
document.addEventListener("DOMContentLoaded", function () {
    const inputCorreo = document.getElementById("correo");
    const CorreoError = document.getElementById("CorreoError");
    const CorreoCheck = document.getElementById("CorreoCheck");
    const mensajeCorreo = document.getElementById("mensajeCorreo");

    if (!inputCorreo) return;

    // Patrón: inicia con minúscula, puede contener letras (may y min), números y símbolos comunes en emails
    const patronCorreo = /^[a-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;

    function validarCorreo() {
        const valor = inputCorreo.value.trim();

        if (valor === "") {
            mensajeCorreo.textContent = "";
            mensajeCorreo.classList.remove("mostrar");
            inputCorreo.classList.remove("input-error", "input-ok");
            CorreoError?.classList.remove("mostrar");
            CorreoCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patronCorreo.test(valor)) {
            mensajeCorreo.textContent = "Correo inválido. Debe iniciar con minúscula y ser un correo válido.";
            mensajeCorreo.classList.add("mostrar");
            inputCorreo.classList.add("input-error");
            inputCorreo.classList.remove("input-ok");
            CorreoError?.classList.add("mostrar");
            CorreoCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeCorreo.textContent = "";
            mensajeCorreo.classList.remove("mostrar");
            inputCorreo.classList.remove("input-error");
            inputCorreo.classList.add("input-ok");
            CorreoError?.classList.remove("mostrar");
            CorreoCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarCorreo = validarCorreo;

    // Evento input para validar en tiempo real y autocompletar @gmail.com al tipear @
    inputCorreo.addEventListener("input", function () {
        let valor = inputCorreo.value;

        // Si el usuario acaba de tipear @ y no hay nada después, autocompleta con gmail.com
        if (valor.endsWith("@")) {
            inputCorreo.value = valor + "gmail.com";
        }

        validarCorreo();
    });

    // Validar al enviar formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarCorreo()) e.preventDefault();
        });
    }
});