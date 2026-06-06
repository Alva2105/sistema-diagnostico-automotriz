document.addEventListener("DOMContentLoaded", () => {

    const tipo = localStorage.getItem("tipo_mantenimiento");
    const nombresServicios = JSON.parse(localStorage.getItem("serviciosNombres")) || [];
    const iconosServicios = JSON.parse(localStorage.getItem("serviciosIconos")) || [];
    const preciosServicios = JSON.parse(localStorage.getItem("serviciosPrecios")) || [];

    // === CAMPOS DE LA VISTA ===
    const tipoMantDisplay = document.getElementById("tipo_mant_display");
    const tipoMantHidden = document.getElementById("tipo_mant_hidden");
    const servicioSummary = document.getElementById("servicioSummary");
    const servicioList = document.getElementById("servicioList");
    const servicioContainer = document.getElementById("servicioContainer");
    const servicioHidden = document.getElementById("servicio_hidden");

    if (!servicioContainer) {
        console.warn("⚠ multiservicios.js: No existe la sección de servicios en este formulario.");
        return;x    
    }

    // === 1️⃣ TIPO DE MANTENIMIENTO ===
    if (tipo) {
        tipoMantDisplay.value = tipo;
        tipoMantHidden.value = tipo;
    }

    // === 2️⃣ SIN SERVICIOS — fallback ===
    if (nombresServicios.length === 0) {
        servicioSummary.innerText = "No se seleccionó ningún servicio";
        servicioHidden.value = "";
        return;
    }

    // === 3️⃣ SOLO UN SERVICIO — mostrar icono, precio y estilo bonito ===
    if (nombresServicios.length === 1) {
        const nombre = nombresServicios[0];
        const icon = iconosServicios[0] || "build";
        const precio = preciosServicios[0] || "—";
    
        servicioSummary.innerHTML = `
            <div class="srv-item unico">
                <span class="material-symbols-outlined iconito">${icon}</span>
                <div class="srv-info">
                    <span class="srv-nombre">${nombre}</span>
                    <span class="srv-precio">${precio}</span>
                </div>
            </div>
        `;
    
        servicioHidden.value = nombre;
        return;
    }

    // === 4️⃣ VARIOS SERVICIOS → DROPDOWN ===
    servicioSummary.innerText = `${nombresServicios.length} servicios seleccionados`;
    
    servicioList.innerHTML = "";
    
    nombresServicios.forEach((nombre, i) => {
        const icon = iconosServicios[i] || "build";
        const precio = preciosServicios[i] || "—";
    
        const li = document.createElement("li");
        li.classList.add("srv-item");
        li.innerHTML = `
            <span class="material-symbols-outlined iconito">${icon}</span>
            <div class="srv-info">
                <span class="srv-nombre">${nombre}</span>
                <span class="srv-precio">${precio}</span>
            </div>
        `;
    
        servicioList.appendChild(li);
    });

    servicioHidden.value = nombresServicios.join(", ");

    // === 5️⃣ TOGGLE DROPDOWN ===
    servicioContainer.addEventListener("click", () => {
        servicioList.classList.toggle("show");
    });

});