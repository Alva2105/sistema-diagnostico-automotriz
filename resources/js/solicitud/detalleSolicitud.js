document.addEventListener('DOMContentLoaded', () => {
    let tecnicoSeleccionado = null;
    let solicitudAbierta = null;

    window.mostrarDetalles = function(el) {
        const data = {
            id: el.dataset.id,
            descripcion: el.dataset.descripcion,
            hrpreferida: el.dataset.hrpreferida,
            fecpreferida: el.dataset.fecpreferida,
            vehiculo: el.dataset.vehiculo,
            servicio: el.dataset.servicio,
            estado: el.dataset.estado
        };

        abrirDetalles(data);
    };

    window.abrirDetalles = function(data) {
        const modal = document.getElementById("modalBack");
        if (!modal) return; // ← guard condition

        modal.style.display = "flex";
        solicitudAbierta = data.id;
        modal.dataset.codSol = data.id;

        document.getElementById("f_vehiculo").textContent = data.vehiculo ?? '—';
        document.getElementById("f_servicio").textContent = data.servicio ?? '—';
        document.getElementById("f_fecpref").textContent = data.fecpreferida ?? '—';
        document.getElementById("f_hrpref").textContent = data.hrpreferida ?? '—';
        document.getElementById("f_estado").textContent = data.estado ?? '—';
        document.getElementById("f_desc").textContent = data.descripcion ?? 'Sin comentarios adicionales.';

        const select = document.getElementById('selectTecnico');
        if (!select) return;

        if (tecnicoSeleccionado && select.querySelector(`option[value="${tecnicoSeleccionado.cod_usuarios}"]`)) {
            select.value = tecnicoSeleccionado.cod_usuarios;
        } else {
            select.value = '';
            tecnicoSeleccionado = null;
        }
    };

    window.cerrarModal = function (ev) {
        const modal = document.getElementById("modalBack");
        if (!modal) return; // ← guard condition
        modal.style.display = "none";
        // si se llama por click en fondo o boton
        document.getElementById("modalBack").style.display = "none";
        // no borramos tecnicoSeleccionado (permanece hasta que cambies) — opcional
    };

    // Manejo del select de técnicos
    const selectTecnico = document.getElementById('selectTecnico');
    if (selectTecnico) {
        selectTecnico.addEventListener('change', function () {
            if (this.value) {
                tecnicoSeleccionado = {
                    cod_usuarios: this.value,
                    nombre: this.options[this.selectedIndex].text
                };
            } else {
                tecnicoSeleccionado = null;
            }
        });
    }

    // Función que se llama desde el botón en la fila: Aprobar solicitud
    // Verifica que exista un técnico seleccionado y que este corresponda (opcional)
    window.aprobarSolicitud = function(cod_sol) {
        // Primero validar que haya un técnico seleccionado
        if (!tecnicoSeleccionado) {
            alert('Primero debe abrir la solicitud (Mostrar Detalles) y seleccionar un técnico en el modal.');
            return;
        }

        // (Opcional) si se desea requerir que la solicitud abierta sea la misma que el cod_sol:
        const modal = document.getElementById('modalBack');
        const abierta = modal ? modal.dataset.codSol : null;
        if (abierta && abierta !== String(cod_sol)) {
            // el técnico seleccionado corresponde a otra solicitud abierta
            if (!confirm('El técnico seleccionado corresponde a otra solicitud abierta. ¿De verdad desea asignarlo a esta solicitud?')) {
                return;
            }
        }

        // Confirmación final
        if (!confirm('¿Confirmas aprobar la solicitud y asignar el técnico seleccionado?')) return;

        // Preparar payload
        const payload = new FormData();
        payload.append('cod_usuarios_asi', tecnicoSeleccionado.cod_tau);

        // CSRF
        const token = document.querySelector('meta[name="csrf-token"]').content;

        fetch(`/gerente/solicitud/${cod_sol}/aprobar`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token
            },
            body: payload
        })
        .then(r => r.json())
        .then(res => {
            if (res.ok) {
                // éxito: puedes actualizar la fila, cerrar modal o recargar
                alert(res.msg || 'Solicitud aprobada correctamente');
                // ejemplo: recargar la página para ver cambios
                window.location.reload();
            } else {
                alert(res.msg || 'Ocurrió un error al aprobar la solicitud.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Error en la petición. Revisa la consola.');
        });
    };

});
