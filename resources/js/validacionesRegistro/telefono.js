//! VALIDACIÓN PARA NÚMERO DE TELÉFONO
document.addEventListener("DOMContentLoaded", function () {
    const inputTelefono = document.getElementById("telefono");
    const mensajeTelefono = document.getElementById("mensajeTelefono");
    const TelefonoError = document.getElementById("TelefonoError");
    const TelefonoCheck = document.getElementById("TelefonoCheck");

    if (!inputTelefono) return;

    // Permitir solo números
    inputTelefono.addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, "");
    });

    function validarTelefono() {
        const valor = inputTelefono.value.trim();
        const patron = /^\d{8}$/;

        if (valor === "") {
            mensajeTelefono.textContent = "";
            mensajeTelefono.classList.remove("mostrar");
            inputTelefono.classList.remove("input-error", "input-ok");
            TelefonoError?.classList.remove("mostrar");
            TelefonoCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patron.test(valor)) {
            mensajeTelefono.textContent = "El número de teléfono debe tener exactamente 8 dígitos.";
            mensajeTelefono.classList.add("mostrar");
            inputTelefono.classList.add("input-error");
            inputTelefono.classList.remove("input-ok");
            TelefonoError?.classList.add("mostrar");
            TelefonoCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeTelefono.textContent = "";
            mensajeTelefono.classList.remove("mostrar");
            inputTelefono.classList.remove("input-error");
            inputTelefono.classList.add("input-ok");
            TelefonoError?.classList.remove("mostrar");
            TelefonoCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarTelefono = validarTelefono;

    // Listener en tiempo real
    inputTelefono.addEventListener("input", validarTelefono);

    // Validación al enviar el formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarTelefono()) e.preventDefault();
        });
    }
});