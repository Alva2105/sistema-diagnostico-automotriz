function estadoKeyFromText(text) {
    if (!text) return '';

    // Normaliza: quita acentos
    const sinAcentos = text.normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    // Lowercase + guiones
    const key = sinAcentos.toLowerCase()
        .replace(/\s+/g, '-') // espacios → guiones
        .replace(/[^a-z0-9\-]/g, ''); // caracteres raros fuera

    // Mapeos seguros
    if (key.includes('agotado')) return 'agotado';
    if (key.includes('por-agotarse') || key.includes('poragotarse') || key.includes('por-agotar')) return 'por-agotarse';
    if (key.includes('nivel') && key.includes('reorden')) return 'reorden';
    if (key.includes('almacen')) return 'almacen';

    return key;
}

window.activarEdicion = function (boton) {

    const fila = boton.closest("tr");
    const span = fila.querySelector(".texto-cantidad");
    const input = fila.querySelector(".input-cantidad");

    // Mostrar input
    span.style.display = "none";
    input.style.display = "inline-block";
    input.focus();

    // Cambiar botón a "Guardar"
    boton.textContent = "Guardar";
    boton.style.backgroundColor = "#22c55e"; // verde
    boton.style.color = "#fff";

    // Cambiar acción del botón
    boton.onclick = function () {
        guardarNuevaCantidad(boton);
    };
};

window.guardarNuevaCantidad = function (boton) {
    const fila = boton.closest("tr");
    const input = fila.querySelector(".input-cantidad");
    const span = fila.querySelector(".texto-cantidad");

    const id = boton.dataset.id;
    const nre = parseInt(boton.dataset.nre) || 0;
    const cse = parseInt(boton.dataset.cse) || 0;
    const nuevaCantidad = parseInt(input.value, 10);

    fetch(`/gerente/repuesto/${id}/actualizar-stock-inline`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ cantidad: nuevaCantidad, nre: nre, cse: cse })
    })
        .then(res => {
            if (!res.ok) throw res;
            return res.json();
        })
        .then(data => {
            if (data.ok) {
                // actualiza la UI con lo que realmente está en la DB
                span.textContent = data.cantidad;

                // obtener key de estado y aplicarla
                const estadoTexto = data.estado || ''; // ej "EN ALMACÉN"
                const estadoKey = estadoKeyFromText(estadoTexto); // ej "almacen", "reorden"
                const celdaEstado = fila.querySelector(".td-inventario:nth-child(4)");
                celdaEstado.innerHTML = `<span class="estado ${estadoKey}">${estadoTexto}</span>`;

                // también actualizamos el botón (habilitar/deshabilitar)
                const botonEditar = fila.querySelector(".btn-editar");
                if (estadoKey === "reorden" || estadoKey === "por-agotarse") {
                    if (botonEditar) {
                        botonEditar.disabled = false;
                        botonEditar.classList.remove('disabled-btn');
                        botonEditar.style.pointerEvents = ''; // por si se modificó
                    }
                } else {
                    if (botonEditar) {
                        botonEditar.disabled = true;
                        botonEditar.classList.add('disabled-btn');
                        botonEditar.style.pointerEvents = 'none';
                    }
                }

                // ocultar input y resetear botón de edición
                input.style.display = "none";
                span.style.display = "inline";
                boton.textContent = "Ajustar Stock";
                boton.style.backgroundColor = "";
                boton.style.color = "";
                boton.onclick = function () { activarEdicion(boton); };

                mostrarToastInv("✔ Cantidad en Stock Ajustada");
            } else {
                mostrarToast("❌ No se pudo actualizar");
            }
        })
        .catch(err => {
            console.error('Error actualizando stock:', err);
            mostrarToast("❌ Error al actualizar");
        });
};

window.actualizarEstadoFila = function (fila, cantidad, nre, cse) {
    cantidad = parseInt(cantidad);

    // determinamos el texto de estado (igual que en server)
    let nuevoEstado = "";
    if (cantidad === 0) {
        nuevoEstado = "AGOTADO";
    } else if (cantidad <= cse) {
        nuevoEstado = "POR AGOTARSE";
    } else if (cantidad <= nre) {
        nuevoEstado = "NIVEL DE REORDEN";
    } else {
        nuevoEstado = "EN ALMACÉN";
    }

    const estadoKey = estadoKeyFromText(nuevoEstado); // normaliza a 'almacen','reorden',...
    const celdaEstado = fila.querySelector(".td-inventario:nth-child(4)");
    celdaEstado.innerHTML = `<span class="estado ${estadoKey}">${nuevoEstado}</span>`;

    // ===== DESHABILITAR O HABILITAR BOTÓN SEGÚN ESTADO =====
    const boton = fila.querySelector(".btn-editar");

    if (boton) {
        if (estadoKey === "reorden" || estadoKey === "por-agotarse") {
            boton.disabled = false;
            boton.classList.remove("disabled-btn");
            boton.style.pointerEvents = '';
        } else {
            boton.disabled = true;
            boton.classList.add("disabled-btn");
            boton.style.pointerEvents = 'none';
        }
    }
};

window.mostrarToastInv = function (msg) {
    const toast = document.getElementById("inv-toast");
    toast.textContent = msg;
    toast.classList.add("show");
    setTimeout(() => toast.classList.remove("show"), 2600);
};
