let chart = null;

window.mostrarGrafico = function (actual, reorden, seguridad, nombre) {
    document.getElementById('modalGrafico').style.display = 'flex';

    // Textos
    document.getElementById('tituloGrafico').innerText = "Nivel de Stock: " + nombre;
    document.getElementById('stockActual').innerText = actual + " unidades";
    document.getElementById('stockReorden').innerText = reorden + " unidades";
    document.getElementById('stockSeguridad').innerText = seguridad + " unidades";

    const ctx = document.getElementById('graficoStock').getContext('2d');

    if (chart !== null) chart.destroy();

    // === COLOR GRADIENTE SEGÚN PELIGRO ===
    const gradient = ctx.createLinearGradient(0, 0, 600, 0);
    gradient.addColorStop(0, "#ff9f43");   // inicio naranja
    gradient.addColorStop(0.4, "#28c76f"); // verde
    gradient.addColorStop(0.7, "#007bff"); // azul
    gradient.addColorStop(1, "#ff3b3b");   // rojo

    // === PLUGIN PARA LÍNEA VERTICAL BLANCA ===
    const verticalLinePlugin = {
        id: "verticalLine",
        afterDraw(chart) {
            const meta = chart.getDatasetMeta(0);
            const x = meta.data[0].x;
            const ctx = chart.ctx;

            ctx.save();
            ctx.beginPath();
            ctx.moveTo(x, chart.chartArea.top);
            ctx.lineTo(x, chart.chartArea.bottom);
            ctx.strokeStyle = "#ffffff"; // BLANCO
            ctx.lineWidth = 2;
            ctx.setLineDash([4, 4]);
            ctx.stroke();
            ctx.restore();
        }
    };

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Actual", "Tendencia", "Reorden", "Seguridad"],
            datasets: [{
                label: 'Nivel de Stock',
                data: [
                    actual,
                    Math.max(reorden + (actual - reorden) * 0.4, seguridad),
                    reorden,
                    seguridad
                ],
                borderColor: gradient,
                borderWidth: 4,
                tension: 0.4,

                // ==== PUNTOS ====
                pointRadius: [12, 8, 8, 8],
                pointBackgroundColor: [
                    "#00ff7bff",  // CYAN brillante punto actual
                    "#ffa500",
                    "#007bff",
                    "#ff3b3b"
                ],
                pointBorderColor: "white",
                pointBorderWidth: 2
            }]
        },
        options: {
            plugins: {
                tooltip: {
                    enabled: true,
                    backgroundColor: "#000",
                    titleColor: "#fff",
                    bodyColor: "#fff"
                },
                legend: {
                    labels: { color: "white", font: { size: 14 } }
                }
            },
            scales: {
                x: {
                    ticks: { color: "white", font: { size: 14 } },
                    grid: { color: "#ffffff33" }
                },
                y: {
                    ticks: { color: "white", font: { size: 14 } },
                    grid: { color: "#ffffff22" }
                }
            }
        },
        plugins: [verticalLinePlugin]
    });
};

window.cerrarGrafico = function () {
    document.getElementById('modalGrafico').style.display = 'none';
};