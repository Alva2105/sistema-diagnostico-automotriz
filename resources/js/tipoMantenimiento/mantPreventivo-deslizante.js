document.addEventListener("DOMContentLoaded", () => {
    const btnPreventivo = document.getElementById("btnPreventivo");
    const container = document.getElementById("servicio-container");
    const panelCorrectivo = document.getElementById("panel-correctivo");

    if (btnPreventivo && container && panelCorrectivo) {
        btnPreventivo.addEventListener("click", async () => {
            // Oculta el botón de servicios del panel derecho
            panelCorrectivo.classList.add("hide-btn");

            // Carga del contenido mediante fetch
            const response = await fetch(btnPreventivo.dataset.url);
            const html = await response.text();

            // Inserta el contenido dentro del panel derecho
            container.innerHTML = `
                <button class="close-btn" id="closeServicio">&times;</button>
                <div class="panel-scroll">${html}</div>
            `;
            container.classList.add("active", "from-right");

            // Activa el botón de cierre
            const closeBtn = document.getElementById("closeServicio");
            closeBtn.addEventListener("click", () => {
                container.classList.add("closing", "from-right");
                setTimeout(() => {
                    container.classList.remove("active", "closing", "from-right");
                    container.innerHTML = "";
                    panelCorrectivo.classList.remove("hide-btn");
                }, 600);
            });
        });
    }
});