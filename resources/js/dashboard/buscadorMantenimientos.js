document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchInput');
    const icon = document.getElementById('searchIcon');

    if (!input || !icon) return;

    const tbody = document.querySelector('table tbody');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    // 🔁 Recargar tabla completa
    async function cargarTablaOriginal() {
        try {
            const response = await fetch('/dashboard/mantenimientos');
            const text = await response.text();
            const temp = document.createElement('div');
            temp.innerHTML = text;
            const newTbody = temp.querySelector('table tbody');
            if (newTbody) tbody.innerHTML = newTbody.innerHTML;
        } catch (error) {
            console.error('Error al recargar tabla:', error);
        }
    }

    // 🔎 Buscar mantenimientos
    async function buscarMantenimientos() {
        const query = input.value.trim();

        if (query === '') {
            await cargarTablaOriginal();
            return;
        }

        try {
            const response = await fetch(`/dashboard/mantenimientos/buscar?q=${encodeURIComponent(query)}`, {
                headers: { 'X-CSRF-TOKEN': csrf }
            });

            const data = await response.json();
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; color:#666;">No se encontraron resultados</td></tr>`;
                return;
            }

            data.forEach(mantenimiento => {
                const cliente = mantenimiento.cliente?.usuario?.registro;
                const usuario = mantenimiento.cliente?.usuario;

                // 🧠 HTML del perfil del cliente
                const clienteHTML = cliente
                    ? `
                        <div class="perfil-cliente">
                            ${
                                usuario?.img_usu && usuario.img_usu !== 'NULL'
                                    ? `<img src="/storage/${usuario.img_usu}" alt="Perfil" class="img-mini">`
                                    : `<span class="material-symbols-outlined icono-perfil">account_circle</span>`
                            }
                            <span class="nombre-cliente">
                                ${cliente.nom_reg ?? ''} ${cliente.apa_reg ?? ''} ${cliente.ama_reg ?? ''}
                            </span>
                        </div>`
                    : '<span class="material-symbols-outlined icono-perfil">account_circle</span> Sin asignar';

                // 🧾 Render de cada fila
                tbody.innerHTML += `
                    <tr data-id="${mantenimiento.cod_man}">
                        <td>${mantenimiento.cod_man}</td>
                        <td>${clienteHTML}</td>
                        <td>${mantenimiento.tma_man ?? 'Desconocido'}</td>
                        <td><span class="estado ${mantenimiento.est_man?.toLowerCase() ?? 'sin-estado'}">${mantenimiento.est_man ?? 'Sin estado'}</span></td>
                        <td>${mantenimiento.fen_man ?? '-'}</td>
                        <td>${mantenimiento.ffi_man ?? '-'}</td>
                        <td>${mantenimiento.des_man ?? '-'}</td>
                        <td>
                            <button class="btn-editar" onclick="activarEdicionEstadoMantenimiento(this)">Editar</button>
                            <button class="btn-guardar" style="display:none;" onclick="guardarEstadoMantenimiento(this)">Guardar</button>
                            <button class="btn-eliminar">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

        } catch (error) {
            console.error('Error al buscar mantenimientos:', error);
            alert('❌ Error al buscar mantenimientos.');
        }
    }

    // 🔘 Eventos del buscador
    icon.addEventListener('click', buscarMantenimientos);
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') buscarMantenimientos();
    });
    input.addEventListener('input', async function () {
        if (input.value.trim() === '') {
            await cargarTablaOriginal();
        }
    });
});