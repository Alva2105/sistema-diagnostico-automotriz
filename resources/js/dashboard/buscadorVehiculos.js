document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('searchInput');
    const icon = document.getElementById('searchIcon');

    if (!input || !icon) return;

    const tbody = document.querySelector('table tbody');

    async function cargarTablaOriginal()
    {
        location.reload();
    }

    async function buscarVehiculos()
    {
        const query = input.value.trim();

        if (query === '')
        {
            await cargarTablaOriginal();
            return;
        }

        try {

            const response = await fetch(
                `/dashboard/vehiculos/buscar?q=${encodeURIComponent(query)}`
            );

            const data = await response.json();

            tbody.innerHTML = '';

            if (data.length === 0)
            {
                tbody.innerHTML =
                    `<tr>
                        <td colspan="9" style="text-align:center;">
                            No se encontraron resultados
                        </td>
                    </tr>`;

                return;
            }

            data.forEach(vehiculo => {

                tbody.innerHTML += `
                    <tr>

                        <td>${vehiculo.cod_vehiculos}</td>

                        <td>${vehiculo.pla_veh}</td>

                        <td>${vehiculo.mar_veh ?? '-'}</td>

                        <td>${vehiculo.mod_veh ?? '-'}</td>

                        <td>${vehiculo.ani_veh ?? '-'}</td>

                        <td>${vehiculo.col_veh ?? '-'}</td>

                        <td>${vehiculo.tip_veh ?? '-'}</td>

                        <td>
                            ${vehiculo.cliente
                                ? vehiculo.cliente.nom_cli + ' ' + vehiculo.cliente.app_cli
                                : 'Sin cliente'}
                        </td>

                        <td class="acciones">

                            <button
                                class="btn-editar"
                                onclick="editarVehiculo(
                                    '${vehiculo.cod_vehiculos}',
                                    '${vehiculo.cod_clientes_veh}',
                                    '${vehiculo.pla_veh}',
                                    '${vehiculo.mar_veh}',
                                    '${vehiculo.mod_veh}',
                                    '${vehiculo.ani_veh}',
                                    '${vehiculo.col_veh}',
                                    '${vehiculo.tip_veh}'
                                )">
                                Editar
                            </button>

                            <form method="POST"
                                  action="/dashboard/vehiculos/${vehiculo.cod_vehiculos}/${vehiculo.cod_clientes_veh}/eliminar"
                                  style="display:inline-block;"
                                  onsubmit="return confirm('¿Eliminar vehículo?')">

                                <input type="hidden" name="_token"
                                       value="${document.querySelector('meta[name="csrf-token"]').content}">

                                <input type="hidden" name="_method" value="DELETE">

                                <button type="submit"
                                        class="btn-eliminar">
                                    Eliminar
                                </button>

                            </form>

                        </td>

                    </tr>
                `;
            });

        }
        catch (error)
        {
            console.error(error);
        }
    }

    icon.addEventListener('click', buscarVehiculos);

    input.addEventListener('keypress', function(e)
    {
        if (e.key === 'Enter')
        {
            buscarVehiculos();
        }
    });

});