let originalContent = null; // Guarda el HTML original (botones de servicios)

document.addEventListener("click", (e) => {
  /* 1️⃣ ABRIR PANEL (Preventivo o Correctivo) */
  const btnServicio = e.target.closest(".services-btn");
  if (btnServicio) {
    const url = btnServicio.dataset.url;
    const container = document.getElementById("servicio-container");

    if (url && container) {
      // Guardar el contenido original (solo la primera vez)
      if (!originalContent) {
        originalContent = container.innerHTML;
      }

      // Cargar el contenido dinámico del panel
      fetch(url)
        .then((res) => res.text())
        .then((html) => {
          container.innerHTML = html;
          container.classList.add("active", "from-right");
        })
        .catch((err) => console.error("Error al cargar el panel:", err));
    }
  }

  /* 2️⃣ EXPANDIR / CONTRAER DESCRIPCIONES DE SERVICIOS */
  const header = e.target.closest(".service-header");
  if (header && !e.target.closest(".hex-icon")) {
    header.closest(".service-card")?.classList.toggle("active");
  }

  /* 3️⃣ HEXÁGONOS: MARCAR SELECCIÓN (checkbox visual) */
  const hex = e.target.closest(".hex-icon");
  if (hex) {
    hex.classList.toggle("active");
  }

  /* 4️⃣ CERRAR PANEL (botón con id="closePanel") */
  const closeBtn = e.target.closest("#closePanel");
  if (closeBtn) {
    const container = document.getElementById("servicio-container");
    if (!container) return;

    // Detectar desde qué lado se abrió el panel
    const isFromRight = container.classList.contains("from-right");
    const isFromLeft = container.classList.contains("from-left");

    // Agregar clase de animación de salida
    container.classList.add("closing");
    if (isFromRight) {
      container.classList.add("from-right");
    } else if (isFromLeft) {
      container.classList.add("from-left");
    }

    // Restaurar los botones originales después de la animación
    setTimeout(() => {
      container.className = ""; // limpia todas las clases
      container.innerHTML = originalContent || ""; // restaura los botones originales

      // 🔹 Animación suave al reaparecer los botones
      container.classList.add("restaurado");
      setTimeout(() => {
        container
          .querySelectorAll(".services-btn")
          .forEach((btn) => btn.classList.add("visible"));
      }, 50);
    }, 700); // duración igual a la animación CSS
  }
});