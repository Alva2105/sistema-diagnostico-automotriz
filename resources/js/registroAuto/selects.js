document.addEventListener("DOMContentLoaded", () => {

    const marcaHidden = document.querySelector("#marca");
    const marcaCustomInput = document.querySelector("#marca-custom");

    // ==========================
    // SISTEMA UNIFICADO DE SELECTS
    // ==========================
    document.querySelectorAll(".rv-select").forEach(select => {

        const type    = select.dataset.type;  // "tipo" | "marca" | "modelo"
        const trigger = select.querySelector(".rv-select-trigger");
        const text    = select.querySelector(".rv-select-text");
        const options = select.querySelector(".rv-options");
        const hidden  = document.querySelector(`#${type}`);
        const customInput = document.querySelector(`#${type}-custom`);

        // ---------- ABRIR / CERRAR MENÚ ----------
        trigger.addEventListener("click", (e) => {
            e.stopPropagation();

            // 🚫 RESTRICCIÓN: MODELO no se abre si no hay marca
            if (type === "modelo") {
                const marcaValor = (marcaHidden?.value || "").trim();
                const marcaManualActiva =
                    marcaCustomInput &&
                    marcaCustomInput.style.display === "block" &&
                    marcaCustomInput.value.trim() !== "";

                if (!marcaValor && !marcaManualActiva) {
                    // Aquí podrías mostrar un mensaje tipo "Selecciona una marca primero"
                    return; // No abre el menú de modelo
                }
            }

            const estabaAbierto = select.classList.contains("open");

            // Cerrar todos los demás
            document.querySelectorAll(".rv-select").forEach(s => s.classList.remove("open"));

            // Si estaba abierto → lo cierro; si no, lo abro
            if (!estabaAbierto) {
                select.classList.add("open");
            }
        });

        // Cerrar al hacer click fuera
        document.addEventListener("click", (e) => {
            if (!select.contains(e.target)) {
                select.classList.remove("open");
            }
        });

        // ---------- GESTIÓN DE OPCIONES ----------
        options.querySelectorAll(".rv-option").forEach(op => {
            op.addEventListener("click", (e) => {
                e.stopPropagation();

                const value = op.dataset.value;

                // CASO ESCRIBIR MANUALMENTE
                if (value === "_custom") {

                    // Ocultar select y mostrar input
                    select.style.display = "none";
                    if (customInput) {
                        customInput.style.display = "block";
                        customInput.focus();
                    }
                    hidden.value = "";

                    // Si es marca → pasar también modelo a input
                    if (type === "marca") {
                        const modeloSelect  = document.querySelector("#select-modelo");
                        const modeloCustom  = document.querySelector("#modelo-custom");

                        if (modeloSelect)  modeloSelect.style.display = "none";
                        if (modeloCustom) {
                            modeloCustom.style.display = "block";
                            modeloCustom.disabled = false;
                        }
                    }

                    return;
                }

                // SELECCIÓN NORMAL
                text.textContent = op.innerText.trim();
                hidden.value = value;
                select.classList.remove("open");

                // Si es marca → cargar modelos
                if (type === "marca") {
                    cargarModelos(value);
                }
            });
        });

        // ---------- VALIDACIÓN DE INPUT PERSONALIZADO ----------
        if (customInput) {
            customInput.addEventListener("input", () => {
                let val = customInput.value;

                if (type === "tipo" || type === "marca") {
                    // Solo letras
                    val = val.replace(/[^A-Za-z]/g, "");
                }

                if (type === "modelo") {
                    // Letras, números y guion
                    val = val.replace(/[^A-Za-z0-9-]/g, "");
                }

                if (val.length > 0) {
                    val = val.charAt(0).toUpperCase() + val.slice(1);
                }

                customInput.value = val;
                hidden.value = val;
            });
        }
    });


    // =======================================
    // FUNCIÓN DE MODELOS SEGÚN MARCA
    // =======================================
    function cargarModelos(marca) {
        const modelos = {
            Suzuki:    ["Swift", "Dzire", "Grand Vitara", "Vitara", "Jimny", "Fronx"],
            Nissan:    ["Kicks", "Frontier", "Navara"],
            Toyota:    ["Hilux", "Corolla", "Corolla Cross", "RAV4", "Land Cruiser"],
            Changan:   ["CS15", "CS35 Plus", "Alsvin"],
            Hyundai:   ["Creta", "Grand i10", "Accent"],
            Chevrolet: ["Onix", "Tracker", "Spark", "Montana"]
        };

        const lista         = modelos[marca] || [];
        const modeloSelect  = document.querySelector("#select-modelo");
        const modeloOptions = modeloSelect.querySelector(".rv-options");
        const modeloText    = modeloSelect.querySelector(".rv-select-text");
        const modeloHidden  = document.querySelector("#modelo");

        modeloOptions.innerHTML = "";
        modeloText.textContent  = "Seleccione modelo";
        modeloHidden.value      = "";

        lista.forEach(m => {
            const div = document.createElement("div");
            div.classList.add("rv-option");
            div.dataset.value = m;
            div.innerHTML = `<span>${m}</span>`;
            modeloOptions.appendChild(div);

            div.addEventListener("click", (e) => {
                e.stopPropagation();
                modeloText.textContent = m;
                modeloHidden.value = m;
                modeloSelect.classList.remove("open");
            });
        });
    }

});
