<!-- detallesSolicitud.blade.php -->
<div id="modalBack" class="modal-back" onclick="cerrarModal(event)" style="display:none;">
    <div class="modal" role="dialog" aria-modal="true" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="cerrarModal(event)">Cerrar</button>

        <div class="modal-center">
            <h3 id="modalTitle">DETALLES DE LA SOLICITUD</h3>

            <div class="grid-center">

                <div class="field">
                    <label>Vehículo (placa)</label>
                    <div class="value" id="f_vehiculo">—</div>
                </div>

                <div class="field">
                    <label>Servicio solicitado</label>
                    <div class="value" id="f_servicio">—</div>
                </div>

                <div class="field">
                    <label>Fecha preferida</label>
                    <div class="value" id="f_fecpref">—</div>
                </div>

                <div class="field">
                    <label>Hora preferida</label>
                    <div class="value" id="f_hrpref">—</div>
                </div>

                <div class="field">
                    <label>Estado</label>
                    <div class="value" id="f_estado">—</div>
                </div>

                <div class="field" style="grid-column: 1 / -1;">
                    <label>Descripción</label>
                    <div class="value" id="f_desc">Sin comentarios adicionales.</div>
                </div>

                <!-- SELECT DE TÉCNICOS -->
                <div class="field" style="grid-column: 1 / -1; margin-top:12px;">
                    <label>Técnico encargado</label>
                    <select id="selectTecnico" class="input-select" style="width:100%; padding:8px; border-radius:6px; font-size:18px;">
                        <option class="select-title" value="">-- Seleccionar técnico --</option>
                        @foreach($tecnicos as $tecnico)
                            <option value="{{ $tecnico->cod_usuarios }}">
                                {{ $tecnico->nom_usu }} {{ $tecnico->app_usu }} {{ $tecnico->ama_usu }}
                            </option>
                        @endforeach
                    </select>
                    <small class="helper" style="padding:8px; border-radius:6px; font-size:15px;">
                        Selecciona un técnico aquí; luego usa el botón "Aprobar" en la fila para confirmar.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>


@push('style')
    @vite('resources/css/detalleSolicitud.css')
@endpush
