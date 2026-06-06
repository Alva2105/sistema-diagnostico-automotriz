document.addEventListener("DOMContentLoaded", () => {

    const fechaPreferida = document.getElementById("fecha_preferida");

    const hoy = new Date();
    const manana = new Date(hoy);
    const semanaDespues = new Date(hoy);

    // +1 día
    manana.setDate(manana.getDate() + 1);

    // +7 días
    semanaDespues.setDate(semanaDespues.getDate() + 7);

    const formatear = (fecha) =>
        fecha.toISOString().split("T")[0]; // yyyy-mm-dd

    fechaPreferida.min = formatear(manana);
    fechaPreferida.max = formatear(semanaDespues);

    // Validación al cambiar
    fechaPreferida.addEventListener("change", (e) => {
        if (e.target.value < fechaPreferida.min || e.target.value > fechaPreferida.max) {
            alert(`Selecciona una fecha entre ${fechaPreferida.min} y ${fechaPreferida.max}`);
            e.target.value = "";
        }
    });

});
