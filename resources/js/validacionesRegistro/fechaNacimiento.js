//! VALIDACIÓN PARA FECHA DE NACIMIENTO
document.addEventListener("DOMContentLoaded", function () {
    const inputFecha = document.getElementById("fechaNacimiento");
    const FechaError = document.getElementById("FechaError");
    const FechaCheck = document.getElementById("FechaCheck");
    const mensajeFecha = document.getElementById("mensajeFecha");

    if (!inputFecha) return;

    function validarFecha() {
        const fechaIngresada = new Date(inputFecha.value);
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);
        fechaIngresada.setHours(0, 0, 0, 0);

        let edad = hoy.getFullYear() - fechaIngresada.getFullYear();
        const mes = hoy.getMonth() - fechaIngresada.getMonth();
        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaIngresada.getDate())) {
            edad--;
        }

        if (edad < 18 || fechaIngresada.getFullYear() < 1950 || isNaN(fechaIngresada)) {
            mensajeFecha.textContent = "Debe tener al menos 18 años y haber nacido después de 1950.";
            mensajeFecha.classList.add("mostrar");
            inputFecha.classList.add("input-error");
            inputFecha.classList.remove("input-ok");
            FechaError?.classList.add("mostrar");
            FechaCheck?.classList.remove("mostrar");
            return false;
        } else {
            mensajeFecha.textContent = "";
            mensajeFecha.classList.remove("mostrar");
            inputFecha.classList.remove("input-error");
            inputFecha.classList.add("input-ok");
            FechaError?.classList.remove("mostrar");
            FechaCheck?.classList.add("mostrar");
            return true;
        }
    }

    //! Hacer la función accesible globalmente para registroBtn.js
    window.validarFecha = validarFecha;

    // Validación en tiempo real
    inputFecha.addEventListener("input", validarFecha);

    // Validación al enviar formulario
    const formulario = document.getElementById("formRegistro");
    if (formulario) {
        formulario.addEventListener("submit", function (e) {
            if (!validarFecha()) e.preventDefault();
        });
    }
});