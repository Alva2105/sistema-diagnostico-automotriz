const modelosPorMarca = {
    Suzuki: ["Swift", "Dzire", "Grand Vitara", "Vitara", "Jimny", "Fronx"],
    Nissan: ["Kicks", "Frontier", "Navara"],
    Toyota: ["Hilux", "Corolla", "Corolla Cross", "RAV4", "Land Cruiser"],
    Changan: ["CS15", "CS35 Plus", "Alsvin"],
    Hyundai: ["Creta", "Grand i10", "Accent"],
    Ford: ["Ranger", "Explorer", "Territory"],
    Jeep: ["Compass", "Renegade", "Grand Cherokee"],
    Honda: ["CR-V", "HR-V", "Civic"],
    Subaru: ["Forester", "All New Crosstrek"],
    Volvo: ["FH", "FM", "FMX", "B12R", "B420"],
    Chevrolet: ["Onix", "Tracker", "Spark", "Montana"]
};

// ------- SELECT MARCA -------
const marcaSelect = document.getElementById("select-marca");
const modeloSelect = document.getElementById("select-modelo");

marcaSelect.querySelector(".rv-select-trigger").onclick = () => {
    marcaSelect.classList.toggle("open");
};

// Seleccionar marca
marcaSelect.querySelectorAll(".rv-option").forEach(opt => {
    opt.onclick = () => {
        const value = opt.dataset.value;

        // Mostrar selección
        marcaSelect.querySelector(".rv-select-text").innerText = value;
        marcaSelect.classList.remove("open");

        // Activar MODELOS
        modeloSelect.classList.remove("disabled");
        modeloSelect.querySelector(".rv-select-text").innerText = "Seleccionar Modelo";

        // Llenar modelos
        const cont = modeloSelect.querySelector(".rv-options");
        cont.innerHTML = "";

        modelosPorMarca[value].forEach(m => {
            const div = document.createElement("div");
            div.classList.add("rv-option");
            div.innerHTML = `<span>${m}</span>`;
            cont.appendChild(div);

            div.onclick = () => {
                modeloSelect.querySelector(".rv-select-text").innerText = m;
                modeloSelect.classList.remove("open");
            };
        });
    };
});

// ------- SELECT MODELO -------
modeloSelect.querySelector(".rv-select-trigger").onclick = () => {
    if (!modeloSelect.classList.contains("disabled")) {
        modeloSelect.classList.toggle("open");
    }
};