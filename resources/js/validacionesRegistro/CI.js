//! VALIDACIÓN PARA NÚMERO DE CARNET DE IDENTIDAD (CI)
document.addEventListener("DOMContentLoaded", function () {
    const inputCI = document.getElementById("CI");
    const mensajeCI = document.getElementById("mensajeCI");
    const CIError = document.getElementById("CIError");
    const CICheck = document.getElementById("CICheck");

    if (!inputCI) return;

    // Permitir solo números
    inputCI.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, "");
    });

    function validarCI() {
        const valor = inputCI.value.trim();
        const patron = /^\d{8}$/;

        if (valor === "") {
            mensajeCI.textContent = "";
            mensajeCI.classList.remove("mostrar");
            inputCI.classList.remove("input-error", "input-ok");
            CIError?.classList.remove("mostrar");
            CICheck?.classList.remove("mostrar");
            return false;
        }

        if (!patron.test(valor)) {
            mensajeCI.textContent = "El número ingresado para su carnet de identidad debe tener exactamente 8 dígitos.";
            mensajeCI.classList.add("mostrar");
            inputCI.classList.add("input-error");
            inputCI.classList.remove("input-ok");
            CIError?.classList.add("mostrar");
            CICheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeCI.textContent = "";
            mensajeCI.classList.remove("mostrar");
            inputCI.classList.remove("input-error");
            inputCI.classList.add("input-ok");
            CIError?.classList.remove("mostrar");
            CICheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarCI = validarCI;

    // Listener en tiempo real
    inputCI.addEventListener("input", validarCI);

    // Validación al enviar el formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarCI()) e.preventDefault();
        });
    }
});