<div id="modalBack" class="modal-back" onclick="cerrarModal(event)" style="display:none;">
    <div class="modal" role="dialog" aria-modal="true" onclick="event.stopPropagation()">

        <button class="modal-close" onclick="cerrarModal(event)">Cerrar</button>

        <div class="modal-left">

            <form id="form-img-rep" enctype="multipart/form-data">
                @csrf

                <div class="photo repuesto-wrapper">

                    {{-- CAMBIO: se elimina el bloque @php que usaba $r->img_rep y $r->cod_rep
                         ya que img_rep no existe en la nueva BD.
                         La imagen y el data-id los carga el JS cuando el usuario abre el modal. --}}
                    <img id="modalPhoto"
                         src="{{ asset('img/placeholders/item.png') }}"
                         alt="Imagen del repuesto"
                         data-id="">

                    <!-- ÍCONO EDITAR -->
                    <label for="img-rep-input" class="edit-icon-rep">
                        <img src="{{ asset('assets/img/icons/edit-pencil.svg') }}" class="edit-svg">
                    </label>

                    <!-- INPUT DE ARCHIVO OCULTO -->
                    <input type="file" id="img-rep-input" name="img_rep" accept="image/*" hidden>

                </div>
            </form>

            <div class="meta" id="modalMeta">Categoría • Marca</div>
        </div>

        <!-- LADO DERECHO -->
        <div class="modal-right">

            <h3 id="modalTitle">TÍTULO DEL ÍTEM</h3>

            <div class="grid-2">

                <div class="field">
                    <label>Nombre</label>
                    <div class="value" id="f_nombre">—</div>
                </div>

                <div class="field">
                    <label>Categoría</label>
                    <div class="value" id="f_categoria">—</div>
                </div>

                <div class="field">
                    <label>Cant. Máx.</label>
                    <div class="value" id="f_cmax">—</div>
                </div>

                <div class="field">
                    <label>Cant. Mín.</label>
                    <div class="value" id="f_cmin">—</div>
                </div>

                {{-- CAMBIO: Marca y Modelo fueron eliminados de la nueva BD (mar_rep, mod_rep
                     ya no existen en la tabla repuestos). Se reemplazan por Precio y Stock
                     que sí existen en la nueva estructura. --}}
                <div class="field">
                    <label>Precio (Bs.)</label>
                    <div class="value" id="f_precio">—</div>
                </div>

                <div class="field">
                    <label>Stock actual</label>
                    <div class="value" id="f_stock">—</div>
                </div>

            </div>

            <div class="desc">
                <strong>Descripción</strong>
                <p id="f_desc" class="desc-text">—</p>
            </div>

        </div>

    </div>
</div>

@push('style')
    @vite('resources/css/detallesRepuestos.css')
@endpush