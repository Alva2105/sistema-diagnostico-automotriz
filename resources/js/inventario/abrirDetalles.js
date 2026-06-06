document.addEventListener("DOMContentLoaded", () => {

    window.mostrarDetalles = function(el) {

        const data = {
            id:          el.dataset.id,
            nombre:      el.dataset.nombre,
            categoria:   el.dataset.categoria,
            cmax:        el.dataset.cmax,
            cmin:        el.dataset.cmin,
            // CAMBIO: marca y modelo eliminados de la nueva BD
            // Se reemplazan por precio y stock
            precio:      el.dataset.precio,
            stock:       el.dataset.stock,
            descripcion: el.dataset.descripcion,
            foto:        el.dataset.foto
        };

        abrirDetalles(data);
    };

    window.abrirDetalles = function(data) {

        const modal = document.getElementById("modalBack");
        modal.style.display = "flex";

        const modalPhoto = document.getElementById("modalPhoto");

        // Guardar ID del repuesto para AJAX
        modalPhoto.dataset.id = data.id;

        // Imagen — como img_rep no existe en la nueva BD, siempre usamos placeholder
        modalPhoto.src = data.foto || "/img/placeholders/item.png";

        // CAMBIO: antes mostraba "Categoría • Marca"
        // Ahora muestra "Categoría • Precio Bs." ya que marca no existe
        document.getElementById("modalMeta").textContent =
            `${data.categoria} • ${data.precio} Bs.`;

        // Campos
        document.getElementById("f_nombre").textContent    = data.nombre;
        document.getElementById("f_categoria").textContent = data.categoria;
        document.getElementById("f_cmax").textContent      = data.cmax;
        document.getElementById("f_cmin").textContent      = data.cmin;
        // CAMBIO: antes llenaba f_marca y f_modelo (eliminados)
        // Ahora llena f_precio y f_stock
        document.getElementById("f_precio").textContent   = data.precio + " Bs.";
        document.getElementById("f_stock").textContent    = data.stock + " unidades";
        document.getElementById("f_desc").textContent     = data.descripcion;
    };

    window.cerrarModal = function () {
        document.getElementById("modalBack").style.display = "none";
    };

    // CAMBIO: el upload de imagen se deja deshabilitado porque img_rep
    // no existe en la nueva BD. Si en el futuro se agrega la columna,
    // descomentar el bloque de abajo.
    //
    // const inputRep = document.getElementById('img-rep-input');
    // if (inputRep) {
    //     inputRep.addEventListener('change', function () { ... });
    // }

    window.mostrarToast = function(msg) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = msg;

        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 20);
        setTimeout(() => toast.classList.remove('show'), 2500);
        setTimeout(() => toast.remove(), 3000);
    };

});