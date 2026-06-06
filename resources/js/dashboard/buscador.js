document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('searchInput');
    const icon = document.getElementById('searchIcon');

    // 👇 Solo ejecuta el buscador si el input existe
    if (!input || !icon) return;

    const tbody = document.querySelector('table tbody');
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    async function cargarTablaOriginal() {
        try {
            const response = await fetch('/dashboard/usuarios'); // recarga desde la ruta principal
            const text = await response.text();
            const temp = document.createElement('div');
            temp.innerHTML = text;
            const newTbody = temp.querySelector('table tbody');
            if (newTbody) tbody.innerHTML = newTbody.innerHTML;
        } catch (error) {
            console.error('Error al recargar tabla:', error);
        }
    }

    async function buscarUsuarios() {
        const query = input.value.trim();

        if (query === '') {
            await cargarTablaOriginal();
            return;
        }

        try {
            const response = await fetch(`/dashboard/usuarios/buscar?q=${encodeURIComponent(query)}`, {
                headers: { 'X-CSRF-TOKEN': csrf }
            });

            const data = await response.json();
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; color:#666;">No se encontraron resultados</td></tr>`;
                return;
            }

            data.forEach(registro => {
                const usuario = registro.usuario || {};
                const rol = usuario.rol ? usuario.rol.nom_rol : 'Sin rol';
                const estado = usuario.est_usu || 'Sin estado';

                const imgHTML = usuario.img_usu && usuario.img_usu !== 'NULL'
                    ? `<img src="/storage/${usuario.img_usu}" alt="Perfil" class="img-mini">`
                    : `<span class="material-symbols-outlined icono-perfil">account_circle</span>`;

                tbody.innerHTML += `
                    <tr>
                        <td class="id-usuario">${imgHTML} ${registro.cod_reg}</td>
                        <td>${registro.nom_reg} ${registro.apa_reg ?? ''} ${registro.ama_reg ?? ''}</td>
                        <td>${registro.coe_reg}</td>
                        <td>${rol}</td>
                        <td><span class="estado ${estado.toLowerCase()}">${estado}</span></td>
                        <td>
                            <button class="btn-editar">Editar</button>
                            <button class="btn-eliminar">Eliminar</button>
                        </td>
                    </tr>`;
            });

        } catch (error) {
            console.error('Error al buscar:', error);
            alert('❌ Error al buscar usuarios');
        }
    }

    icon.addEventListener('click', buscarUsuarios);
    input.addEventListener('keypress', e => {
        if (e.key === 'Enter') buscarUsuarios();
    });

    input.addEventListener('input', async function() {
        if (input.value.trim() === '') {
            await cargarTablaOriginal();
        }
    });
});
