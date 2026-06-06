document.addEventListener('click', (e) => {

    const config = window.solicitudConfig || {};
    const isLogged = config.isLogged || false;
    const loginRoute = config.loginRoute || '/login';
    const solicitudRoute = config.solicitudRoute || '/solicitudes/crear';

    // === 1️⃣ SELECCIÓN DE SERVICIOS (hexágonos) ===
    const hex = e.target.closest('.hex-icon');
    if (hex) {
        hex.classList.toggle('selected');
        hex.classList.toggle('active');
        return;
    }

    // === 2️⃣ BOTÓN "CREAR SOLICITUD" ===
    const btn = e.target.closest('.btnPreventivo, .btnCorrectivo');
    if (!btn) return;

    const panel = btn.closest('#servicio-container') || document;
    const servicios = panel.querySelectorAll('.hex-icon.selected');

    if (servicios.length === 0) {
        alert('Selecciona al menos un servicio antes de continuar.');
        return;
    }

    // === 3️⃣ TIPO DE MANTENIMIENTO ===
    let tipoMantenimiento = "";
    if (btn.classList.contains("btnPreventivo")) tipoMantenimiento = "Mantenimiento Preventivo";
    if (btn.classList.contains("btnCorrectivo")) tipoMantenimiento = "Mantenimiento Correctivo";

    localStorage.setItem("tipo_mantenimiento", tipoMantenimiento);

    // === 4️⃣ RECOLECTAR INFORMACIÓN COMPLETA DE LOS SERVICIOS ===
    const nombres = [];
    const iconos = [];
    const precios = []; 

    servicios.forEach(s => {    

        const card = s.closest(".service-card");    

        // ✔ nombre
        const title = card.querySelector(".service-title")?.innerText.trim() || "Servicio"; 

        // ✔ icono dentro del hexágono
        const iconEl = card.querySelector(".service-header .service-symbol");
        const icon = iconEl ? iconEl.innerText.trim() : "build";    

        // ✔ precio dentro del servicio
        const precioEl = card.querySelector(".service-cost strong");
        const precio = precioEl ? precioEl.innerText.trim() : "—";  

        nombres.push(title);
        iconos.push(icon);
        precios.push(precio);
    }); 

    localStorage.setItem("serviciosNombres", JSON.stringify(nombres));
    localStorage.setItem("serviciosIconos", JSON.stringify(iconos));
    localStorage.setItem("serviciosPrecios", JSON.stringify(precios));

    // Para display rápido
    const servicioRequerido = (nombres.length === 1) ? nombres[0] : "Múltiples servicios seleccionados";
    localStorage.setItem("servicio_requerido", servicioRequerido);

    // === 5️⃣ GUARDAR ID NUMÉRICO DE CADA SERVICIO ===
    const serviciosSeleccionados = Array.from(servicios).map(s => s.dataset.service);
    localStorage.setItem('serviciosSeleccionados', JSON.stringify(serviciosSeleccionados));

    // === 6️⃣ REDIRECCIÓN ===
    const params = new URLSearchParams();
    serviciosSeleccionados.forEach(id => params.append('servicio[]', id));

    const destino = `${solicitudRoute}?${params.toString()}`;

    if (!isLogged) {
        sessionStorage.setItem('solicitudRedirect', destino);
        window.location.href = loginRoute;
    } else {
        window.location.href = destino;
    }

});