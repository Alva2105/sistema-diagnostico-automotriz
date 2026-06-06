//! VALIDACIÓN PARA DIRECCIÓN
document.addEventListener("DOMContentLoaded", function () {
    const inputDireccion = document.getElementById("direccion");
    const mensajeDireccion = document.getElementById("mensajeDireccion");
    const DireccionError = document.getElementById("DireccionError");
    const DireccionCheck = document.getElementById("DireccionCheck");

    if (!inputDireccion) return;

    // Función para capitalizar la primera letra de cada palabra
    function capitalizarPalabras(texto) {
        return texto
            .toLowerCase()
            .replace(/\b[a-záéíóúñ]/g, (letra) => letra.toUpperCase());
    }

    function validarDireccion() {
        const valor = inputDireccion.value.trim();
        const patron = /^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ#\-,.\/\s]{5,100}$/;

        if (valor === "") {
            mensajeDireccion.textContent = "";
            mensajeDireccion.classList.remove("mostrar");
            inputDireccion.classList.remove("input-error", "input-ok");
            DireccionError?.classList.remove("mostrar");
            DireccionCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patron.test(valor)) {
            mensajeDireccion.textContent = "Ingrese una dirección válida (mínimo 5 caracteres, sin símbolos extraños).";
            mensajeDireccion.classList.add("mostrar");
            inputDireccion.classList.add("input-error");
            inputDireccion.classList.remove("input-ok");
            DireccionError?.classList.add("mostrar");
            DireccionCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeDireccion.textContent = "";
            mensajeDireccion.classList.remove("mostrar");
            inputDireccion.classList.remove("input-error");
            inputDireccion.classList.add("input-ok");
            DireccionError?.classList.remove("mostrar");
            DireccionCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarDireccion = validarDireccion;

    // Validación en tiempo real
    inputDireccion.addEventListener("input", function () {
        let valor = this.value.replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ#\-,.\/\s]/g, "");
        this.value = capitalizarPalabras(valor);
        validarDireccion();
    });

    // Validación al perder el foco
    inputDireccion.addEventListener("blur", validarDireccion);

    // Validación al enviar formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarDireccion()) e.preventDefault();
        });
    }
});