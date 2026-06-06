document.addEventListener("DOMContentLoaded", () => {
    const btnCorrectivo = document.getElementById("btnCorrectivo");
    const container = document.getElementById("servicio-container");
    const panelPreventivo = document.getElementById("panel-preventivo");

    if (btnCorrectivo && container && panelPreventivo) {
        btnCorrectivo.addEventListener("click", async () => {
            // Oculta el botón de servicios del panel preventivo
            panelPreventivo.classList.add("hide-btn");

            // Carga dinámica del contenido
            const response = await fetch(btnCorrectivo.dataset.url);
            const html = await response.text();

            // Inserta el HTML cargado
            container.innerHTML = `
                <button class="close-btn" id="closeServicio">&times;</button>
                <div class="panel-scroll">${html}</div>
            `;
            container.classList.add("active", "from-left");

            // Botón cerrar
            const closeBtn = document.getElementById("closeServicio");
            closeBtn.addEventListener("click", () => {
                container.classList.add("closing", "from-left");
                setTimeout(() => {
                    container.classList.remove("active", "closing", "from-left");
                    container.innerHTML = "";
                    panelPreventivo.classList.remove("hide-btn");
                }, 600);
            });
        });
    }
});