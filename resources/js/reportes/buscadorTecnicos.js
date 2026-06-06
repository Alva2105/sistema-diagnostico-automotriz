document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchTInput');
    const icon = document.getElementById('searchTIcon');

    if (!input || !icon) return;

    const tbody = document.querySelector('.tabla-solicitud tbody');
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.content : '';

    // Debounce helper
    function debounce(fn, wait = 300) {
        let t;
        return function (...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), wait);
        };
    }

    // formato dd/mm/YYYY robusto y flexible
    function formatDateFlexible(value) {
        if (value === null || typeof value === 'undefined' || value === '') return null;

        // Si ya es objeto Date válido
        if (value instanceof Date && !isNaN(value)) {
            const d = value;
            return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
        }

        // Si es número (timestamp)
        if (typeof value === 'number') {
            const ms = value.toString().length === 10 ? value * 1000 : value;
            const d = new Date(ms);
            if (!isNaN(d)) return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
            return null;
        }

        // Si es string, intentar varios formatos
        if (typeof value === 'string') {
            const v = value.trim();

            // ISO YYYY-MM-DD or YYYY-MM-DD HH:MM:SS
            let m = v.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (m) return `${m[3]}/${m[2]}/${m[1]}`;

            // YYYY/MM/DD
            m = v.match(/^(\d{4})\/(\d{2})\/(\d{2})/);
            if (m) return `${m[3]}/${m[2]}/${m[1]}`;

            // DD/MM/YYYY or DD-MM-YYYY
            m = v.match(/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/);
            if (m) return `${m[1]}/${m[2]}/${m[3]}`;

            // "0000-00-00" -> tratar como nulo
            if (/^0{4}[-\/]0{2}[-\/]0{2}$/.test(v)) return null;

            // último intento: Date.parse
            const d = new Date(v);
            if (!isNaN(d)) return `${String(d.getDate()).padStart(2, '0')}/${String(d.getMonth() + 1).padStart(2, '0')}/${d.getFullYear()}`;
        }

        return null;
    }

    async function cargarTablaOriginal() {
        try {
            const response = await fetch('/gerente/listadoTecnicos');
            const text = await response.text();
            const temp = document.createElement('div');
            temp.innerHTML = text;
            const newTbody = temp.querySelector('.tabla-solicitud tbody');
            if (newTbody) {
                tbody.innerHTML = newTbody.innerHTML;
            }
        } catch (err) {
            console.error('Error al recargar tabla original:', err);
        }
    }

    async function buscarTecnicos() {
        const q = input.value.trim();

        if (q === '') {
            await cargarTablaOriginal();
            return;
        }

        try {
            const url = `/gerente/listadoTecnicos/buscar?q=${encodeURIComponent(q)}`;
            const resp = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });

            if (!resp.ok) {
                const text = await resp.text();
                console.error('Respuesta no OK del servidor:', resp.status, text);
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#c00;">Error al buscar técnicos</td></tr>`;
                return;
            }

            const data = await resp.json();

            // Vaciar tbody
            tbody.innerHTML = '';

            if (!Array.isArray(data) || data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#666;">No se encontraron resultados</td></tr>`;
                return;
            }

            // Construir filas respetando exactamente las columnas de la tabla Blade
            data.forEach((t, i) => {
                const usuario = t.usuario || {};
                const registro = usuario.registro || {};
                const foto = usuario.img_usu && usuario.img_usu !== 'NULL' ? usuario.img_usu : null;

                const nombre = `${registro.nom_reg ?? ''} ${registro.apa_reg ?? ''} ${registro.ama_reg ?? ''}`.trim() || '—';
                const ci = registro.cie_reg ?? '—';
                const especialidad = t.esp_tau ?? '—';
                const correo = registro.coe_reg ?? '—';

                // FECHA: usar la función flexible y mostrar '—' si no existe o es inválida
                const rawFna = registro.fna_reg ?? registro.fna ?? null;
                const fna = rawFna ? (formatDateFlexible(rawFna) || '—') : '—';

                const estado = usuario.est_usu ?? 'Desconocido';

                // índice simple (si necesitas el ID real, usa t.cod_tau)
                const indexNumber = i + 1;

                tbody.innerHTML += `
                    <tr>
                        <!-- ID -->
                        <td class="td-solicitud">${indexNumber}</td>

                        <!-- Nombre completo -->
                        <td class="td-solicitud">${nombre}</td>

                        <!-- Teléfono (cie_reg) -->
                        <td class="td-solicitud">${ci}</td>

                        <!-- Especialidad -->
                        <td class="td-solicitud">${especialidad}</td>

                        <!-- Correo -->
                        <td class="td-solicitud">${correo}</td>

                        <!-- Fecha nacimiento -->
                        <td class="td-solicitud">${fna}</td>

                        <!-- Estado -->
                        <td class="td-solicitud">
                            <span class="estado ${String(estado).toLowerCase()}">${estado}</span>
                        </td>

                        <!-- Acción -->
                        <td class="td-solicitud">
                            <button class="btn-aprobar" data-id="${t.cod_tau ?? ''}">Aprobar</button>
                        </td>
                    </tr>
                `;
            });

        } catch (error) {
            console.error('Error al buscar técnicos:', error);
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; color:#c00;">Error al buscar técnicos</td></tr>`;
        }
    }

    const buscarDebounced = debounce(buscarTecnicos, 250);

    // eventos
    icon.addEventListener('click', function (e) {
        e.preventDefault();
        buscarTecnicos();
    });

    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            buscarTecnicos();
        }
    });

    input.addEventListener('input', async () => {
        if (input.value.trim() === '') {
            await cargarTablaOriginal();
        } else {
            buscarDebounced();
        }
    });

    // opcional: cargar tabla original al inicio (descomenta si lo deseas)
    // cargarTablaOriginal();
});
