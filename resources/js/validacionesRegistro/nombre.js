//! VALIDACIÓN PARA NOMBRES
document.addEventListener("DOMContentLoaded", function () {
    const inputNombre = document.getElementById("nombre");
    const mensajeNombre = document.getElementById("mensajeNombre");
    const NameError = document.getElementById("NameError");
    const NameCheck = document.getElementById("NameCheck");

    if (!inputNombre) return;

    function validarNombre() {
        const valor = inputNombre.value.trim();
        const patron = /^([A-ZÁÉÍÓÚÑ][a-záéíóúñ]+)(\s[A-ZÁÉÍÓÚÑ][a-záéíóúñ]+){0,2}$/;

        if (valor === "") {
            mensajeNombre.textContent = "";
            mensajeNombre.classList.remove("mostrar");
            inputNombre.classList.remove("input-error", "input-ok");
            NameError?.classList.remove("mostrar");
            NameCheck?.classList.remove("mostrar");
            return false;
        }

        if (!patron.test(valor)) {
            mensajeNombre.textContent = "Debe contener de 1 a 3 palabras, cada una iniciando con mayúscula seguida de minúsculas, sin símbolos ni números.";
            mensajeNombre.classList.add("mostrar");
            inputNombre.classList.add("input-error");
            inputNombre.classList.remove("input-ok");
            NameError?.classList.add("mostrar");
            NameCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeNombre.textContent = "";
            mensajeNombre.classList.remove("mostrar");
            inputNombre.classList.remove("input-error");
            inputNombre.classList.add("input-ok");
            NameError?.classList.remove("mostrar");
            NameCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarNombre = validarNombre;

    // Listener en tiempo real
    inputNombre.addEventListener("input", validarNombre);

    // Opcional: validar al enviar
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarNombre()) e.preventDefault();
        });
    }
});
