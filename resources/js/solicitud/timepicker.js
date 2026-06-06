document.addEventListener('DOMContentLoaded', () => {

    const inputDisplay  = document.getElementById('hora_preferida');
    const inputReal     = document.getElementById('hora_real');
    const panel         = document.getElementById('timePanel');
    const ulHours       = document.getElementById('tpHours');
    const ulMinutes     = document.getElementById('tpMinutes');
    const ulPeriod      = document.getElementById('tpPeriod');

    // Seguridad: si algo no existe, detenemos el script
    if (!inputDisplay || !inputReal || !panel || !ulHours || !ulMinutes || !ulPeriod) {
        console.error("TimePicker: No se encontró algún elemento HTML necesario.");
        return;
    }

    const MIN_TOTAL = 7 * 60;   // 07:00
    const MAX_TOTAL = 19 * 60;  // 19:00

    let selectedHour = 7;
    let selectedMinute = 0;
    let selectedPeriod = "AM";

    // ==== Helpers ====
    const to24 = (h12, period) => {
        let h = h12 % 12;
        if (period === "PM") h += 12;
        return h;
    };

    const isValidCombo = (h12, m, period) => {
        const h24 = to24(h12, period);
        const total = h24 * 60 + m;
        return total >= MIN_TOTAL && total <= MAX_TOTAL;
    };

    const updateInputValues = () => {
        if (!isValidCombo(selectedHour, selectedMinute, selectedPeriod)) return;

        const h12 = String(selectedHour).padStart(2, "0");
        const m = String(selectedMinute).padStart(2, "0");
        const h24 = String(to24(selectedHour, selectedPeriod)).padStart(2, "0");

        inputDisplay.value = `${h12}:${m} ${selectedPeriod}`;
        inputReal.value    = `${h24}:${m}`;
    };

    const refreshHourStates = () => {
        [...ulHours.querySelectorAll("li")].forEach(li => {
            const h12 = Number(li.dataset.h12);
            const disabled = !isValidCombo(h12, selectedMinute, selectedPeriod);
            li.classList.toggle("tp-disabled", disabled);
        });
    };

    // ==== Poblar horas (1–12) ====
    for (let h = 1; h <= 12; h++) {
        const li = document.createElement("li");
        li.textContent = String(h).padStart(2, "0");
        li.dataset.h12 = h;

        li.addEventListener("click", () => {
            if (li.classList.contains("tp-disabled")) return;
            selectedHour = h;

            ulHours.querySelectorAll("li").forEach(i => i.classList.remove("active"));
            li.classList.add("active");

            updateInputValues();
        });

        ulHours.appendChild(li);
    }

    // ==== Poblar minutos (intervalo 5 min) ====
    for (let m = 0; m < 60; m += 5) {
        const li = document.createElement("li");
        li.textContent = String(m).padStart(2, "0");
        li.dataset.min = m;

        li.addEventListener("click", () => {
            selectedMinute = m;
            ulMinutes.querySelectorAll("li").forEach(i => i.classList.remove("active"));
            li.classList.add("active");

            refreshHourStates();
            updateInputValues();
        });

        ulMinutes.appendChild(li);
    }

    // ==== AM / PM ====
    [...ulPeriod.querySelectorAll("li")].forEach(li => {
        li.addEventListener("click", () => {
            selectedPeriod = li.textContent.trim();

            ulPeriod.querySelectorAll("li").forEach(p => p.classList.remove("active"));
            li.classList.add("active");

            refreshHourStates();
            updateInputValues();
        });
    });

    // ==== Mostrar / ocultar panel ====
    inputDisplay.addEventListener("click", e => {
        e.stopPropagation();
        panel.classList.toggle("show");
    });

    document.addEventListener("click", e => {
        if (!panel.contains(e.target) && e.target !== inputDisplay) {
            panel.classList.remove("show");
        }
    });

    // ==== Estados iniciales ====
    ulHours.querySelector('li[data-h12="7"]').classList.add("active");
    ulMinutes.querySelector('li[data-min="0"]').classList.add("active");
    ulPeriod.querySelector("li:first-child").classList.add("active");

    refreshHourStates();
    updateInputValues();
});
