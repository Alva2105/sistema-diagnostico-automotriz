<div id="req-repuestos-body" class="fade-enter">
    <div class="NEWScard-content">
        <div class="left-cart-panel">
            <div class="cart-header">
                <span class="cart-title">Ítems Seleccionados</span>
                <span class="cart-count" id="cart-count-badge">0</span>
            </div>

            <div class="cart-items-container" id="cart-container">
                <div class="empty-cart-state">
                    <div class="empty-cart-icon">
                        <span class="material-symbols-outlined" style="font-size:90px;">shopping_cart</span>
                    </div>
                    <p>Su lista está vacía</p>
                </div>
            </div>

            <button class="confirm-btn" onclick="sendSelection()">CONFIRMAR SELECCIÓN</button>
        </div>

        <div class="right-interaction-panel">
        
            <div class="categories-grid" id="seguimiento-grid">
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(1, 'Filtros')">
                    <img src="{{ asset('assets/img/inventarios/filtros.png') }}" class="cat-img">
                    <span>Filtros</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(2,'Amortiguadores')">
                    <img src="{{ asset('assets/img/inventarios/amortiguadores.png') }}" class="cat-img">
                    <span>Amortiguadores</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(3,'Muñones')">
                    <img src="{{ asset('assets/img/inventarios/munones.png') }}" class="cat-img">
                    <span>Muñones</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(4,'Bujías')">
                    <img src="{{ asset('assets/img/inventarios/bujias.png') }}" class="cat-img">
                    <span>Bujías</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(5,'Frenos')">
                    <img src="{{ asset('assets/img/inventarios/frenos.png') }}" class="cat-img">
                    <span>Frenos</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(6,'Chicotillos')">
                    <img src="{{ asset('assets/img/inventarios/chicotillo.png') }}" class="cat-img">
                    <span>Chicotillos</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(7,'Kit Embrague')">
                    <img src="{{ asset('assets/img/inventarios/kitenbrague.png') }}" class="cat-img">
                    <span>Kit Embrague</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(8,'Líquidos')">
                    <img src="{{ asset('assets/img/inventarios/litros_liquido.png') }}" class="cat-img">
                    <span>Líquidos</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(9,'Aceites')">
                    <img src="{{ asset('assets/img/inventarios/aceite.png') }}" class="cat-img">
                    <span>Galones</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(10,'Grasas')">
                    <img src="{{ asset('assets/img/inventarios/tarros.png') }}" class="cat-img">
                    <span>Grasas</span>
                </button>
            
                <button class="cat-btn img-btn" onclick="openSeguimientoCategory(11,'Generales')">
                    <img src="{{ asset('assets/img/inventarios/juegos.png') }}" class="cat-img">
                    <span>Generales</span>
                </button>
            
            </div>
        
            <!-- Vista detalle -->
            <div class="detail-view" id="seguimiento-detail">
                <div class="detail-header" onclick="closeSeguimientoCategory()">
                    <span class="detail-title-text" id="seguimiento-title">Categoría</span>
                    <div class="back-btn-wrap"><span>↩ Volver al menú</span></div>
                </div>
            
                <div class="detail-list" id="seguimiento-list"></div>
            </div>
        
        </div>
    </div>

    {{-- Estilos propios --}}
<style>
    :root {
        --neon-orange: #ff7b00;
        --deep-orange: #e65c00;
        --bg-panel: #1a1a1a;
        --bg-dark: #0f0f0f;
        --bg-light-dark: #252525;
        --bg-mid-dark: #1d1d1d;
        --text-white: #fff;
        --text-muted: #b5b5b5;
        --border-dark: #333;
    }

    /* Forzar el contenedor del modal a ocupar TODO el ancho de la página */
    #req-repuestos-body {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
    }

    /* === TARJETA GENERAL === */
    .NEWScard-content {
        max-width: 2300px;
        width: 100%;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 32% 68%;
        gap: 0;
        background: var(--bg-panel);
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,0,0,0.45);
        border: 1px solid #222;
    }

    /* ============================================
        PANEL IZQUIERDO
       ============================================ */
    .left-cart-panel {
        background: #151515;
        padding: 30px;
        display: flex;
        flex-direction: column;
        border-right: 1px solid var(--border-dark);
    }

    /* Header del carrito */
    .cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-dark);
        margin-bottom: 20px;
    }

    .cart-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-white);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .cart-count {
        background: var(--neon-orange);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        color: #000;
    }

    /* Items */
    .cart-items-container {
        flex: 1;
        overflow-y: auto;
        padding-right: 10px;
    }

    .cart-items-container::-webkit-scrollbar {
        width: 6px;
    }
    .cart-items-container::-webkit-scrollbar-thumb {
        background-color: var(--bg-light-dark);
        border-radius: 3px;
    }

    /* Estado vacío */
    .empty-cart-state {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 80%;
        color: var(--text-muted);
        text-align: center;
    }

    .empty-cart-icon {
        font-size: 3rem;
        opacity: 0.25;
        margin-bottom: 10px;
    }

    /* Botón Confirmar */
    .confirm-btn {
        background: linear-gradient(45deg, var(--neon-orange), var(--deep-orange));
        color: #fff;
        border: none;
        padding: 14px;
        margin-top: 25px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 18px rgba(255, 120, 0, 0.25);
        transition: 0.25s ease;
    }

    .confirm-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 28px rgba(255,120,0,0.55);
    }

    /* Ítem en el carrito (Oscuro) */
    .cart-item {
        background-color: var(--panel-light-dark); padding: 12px; margin-bottom: 12px;
        border-radius: 8px; display: flex; align-items: center; border: 1px solid transparent;
        transition: 0.3s;
    }

    .cart-item:hover {
        border-color: var(--neon-orange);
    }

    .qty-control {
        display: flex;
        align-items: center;
        background: #222;
        border-radius: 20px;
        margin-right: 12px;
        padding: 2px; 
        border: 1px solid #444;
    }

    .qty-btn { 
        background: none;
        border: none; 
        width: 28px; height: 28px; color: var(--text-white);
        height: 28px; 
        color: var(--text-white);
        cursor: pointer;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: 0.2s;
    }

    .qty-btn:hover {
        background-color: rgba(255,255,255,0.1);
        color: var(--neon-orange); 
    }

    .qty-val {
        font-weight: bold;
        padding: 0 10px;
        font-size: 0.9rem;
        color: var(--text-white);
    }

    .item-text {
        flex: 1;
        font-weight: 500;
        font-size: 0.85rem;
        line-height: 1.3;
        color: var(--text-white);
    }

    .del-btn {
        background: none;
        color: #ff4444;
        border: none;
        width: 30px;
        height: 30px;
        margin-left: 5px;
        cursor: pointer;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
        transition: 0.3s;
    }

    .del-btn:hover {
        opacity: 1;
        transform: scale(1.1); 
    }
    /* ============================================
        PANEL DERECHO
       ============================================ */

    .right-interaction-panel {
        padding: 40px;
        background: var(--bg-panel);
    }

    /* =======================
        GRID DE CATEGORÍAS
        =======================*/
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 22px;
    }

    #seguimiento-list {
        max-height: calc(75vh - 420px);
        overflow-y: auto;
        padding-right: 10px;
        scrollbar-width: thin;
        scrollbar-color: #ff8800 #111;
    }

    #seguimiento-list::-webkit-scrollbar {
        width: 8px;
    }
    #seguimiento-list::-webkit-scrollbar-thumb {
        background-color: #ff8800;
        border-radius: 10px;
    }
    #seguimiento-list::-webkit-scrollbar-track {
        background: #111;
    }

    .img-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }

    .cat-img {
        width: 48px;
        height: 48px;
        object-fit: contain;
        opacity: .9;
        transition: .25s ease;
    }

    .img-btn:hover .cat-img {
        transform: translateY(-3px) scale(1.05);
        opacity: 1;
    }

    .cat-btn {
        background: var(--bg-light-dark);
        padding: 25px 20px;
        border-radius: 12px;
        color: var(--text-white);
        cursor: pointer;
        border: 2px solid transparent;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 120px;
        transition: all 0.25s ease;
        box-shadow: 0 0 0 rgba(0,0,0,0);
        position: relative;
    }

    .cat-btn::after {
        content: '›';
        position: absolute;
        bottom: 12px;
        right: 16px;
        font-size: 1.6rem;
        opacity: 0.35;
        transition: 0.25s ease;
    }

    .cat-btn:hover {
        background: #333;
        border-color: var(--neon-orange);
        transform: translateY(-6px);
        box-shadow: 0 6px 18px rgba(255, 120, 0, 0.25);
    }

    .cat-btn:hover::after {
        opacity: 1;
        transform: translateX(5px);
        color: var(--neon-orange);
    }

    .cat-btn span {
        font-size: 1rem;
        font-weight: 700;
        margin-top: 8px;
    }

    /* ============================================
        LISTA DE DETALLE LATERAL
       ============================================ */

    .detail-view {
        display: none;
        flex-direction: column;
        background: var(--bg-mid-dark);
        border-radius: 10px;
        height: 100%;
        animation: fadeIn 0.3s ease;
    }

    .detail-header {
        padding: 18px 25px;
        background: linear-gradient(90deg, #2a2a2a, #1a1a1a);
        border-bottom: 1px solid #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }

    .detail-title-text {
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--neon-orange);
    }

    .back-btn-wrap {
        padding: 6px 15px;
        background: rgba(255,255,255,0.05);
        border-radius: 15px;
        color: var(--text-muted);
        transition: 0.25s ease;
    }

    .detail-header:hover .back-btn-wrap {
        background: rgba(255,123,0,0.25);
        color: var(--text-white);
    }

    .detail-list {
        padding: 25px;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
        flex: 1;
        overflow-y: auto;
    }

    /* Item seleccionable */
    .selectable-item {
        background: var(--bg-light-dark);
        padding: 15px 20px;
        border-radius: 10px;
        color: #ddd;
        border: 1px solid #222;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.25s ease;
    }

    .selectable-item:hover {
        background: #363636;
        border-color: var(--neon-orange);
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
        transform: translateY(-4px);
    }

    .selectable-item::before {
        content: '+';
        margin-right: 10px;
        color: var(--neon-orange);
        opacity: 0;
        transition: 0.2s;
    }

    .selectable-item:hover::before {
        opacity: 1;
    }

</style>

<script>
window.openSeguimientoCategory = async function (invId, name, sortByCod = false) {
    const mainBody = document.getElementById("main-body");
    const reqBody  = document.getElementById("req-repuestos-body");
    const segDetail = document.getElementById('seguimiento-detail');
    const segGrid   = document.getElementById('seguimiento-grid');
    const segTitle  = document.getElementById('seguimiento-title');
    const segList   = document.getElementById('seguimiento-list');

    if (mainBody) mainBody.style.display = "none";
    if (reqBody) reqBody.style.display = "block";

    if (segDetail) segDetail.style.display = "flex";
    if (segGrid) segGrid.style.display = "none";

    if (segTitle) segTitle.textContent = name;
    if (segList) segList.innerHTML = "<p style='color:white'>Cargando...</p>";

    try {
        const resp = await fetch(`/api/repuestos/${invId}`);
        let repuestos = await resp.json();

        if (!segList) return;

        if (sortByCod && Array.isArray(repuestos)) {
            repuestos.sort((a, b) => (parseInt(a.cod_rep, 10) || 0) - (parseInt(b.cod_rep, 10) || 0));
        }

        segList.innerHTML = "";

        if (!Array.isArray(repuestos) || repuestos.length === 0) {
            segList.innerHTML = "<p style='color:white'>No hay repuestos.</p>";
            return;
        }

        repuestos.forEach(r => {
            // ✅ usar r.stock en lugar de r.cant_rep
            const stockActual = Number(r.stock) || 0;
            const fullName    = `${r.nom_rep} (${r.mod_rep ?? '—'})`;

            const item = document.createElement("div");
            item.className = "selectable-item";
            item.dataset.cod    = r.cod_repuestos;
            item.dataset.nombre = fullName;
            item.dataset.stock  = stockActual;

            item.innerHTML = `
                <span class="item-name">${fullName}</span>
                <span class="item-stock" style="
                    font-size:11px;
                    color: ${stockActual > 5 ? '#66bb6a' : stockActual > 0 ? '#ffc107' : '#e57373'};
                    display:block;
                    margin-top:4px;
                ">
                    Stock: ${stockActual > 0 ? stockActual : '⚠ Sin stock'}
                </span>
            `;

            // Deshabilitar si no hay stock
            if (stockActual === 0) {
                item.style.opacity = '0.4';
                item.style.cursor  = 'not-allowed';
            } else {
                item.onclick = () => window.addToCart(r.cod_repuestos, fullName, stockActual);
            }

            segList.appendChild(item);
        });

    } catch (e) {
        console.error('Error cargando repuestos:', e);
        if (segList) segList.innerHTML = "<p style='color:red'>Error al cargar.</p>";
    }
};

window.backToCategories = function () {
    const segDetail = document.getElementById('seguimiento-detail');
    const segGrid   = document.getElementById('seguimiento-grid');

    if (segDetail) segDetail.style.display = "none";
    if (segGrid) segGrid.style.display = "grid"; // o 'flex' si tu CSS usa flex
};

window.closeSeguimientoCategory = function () {
    const main = document.getElementById("main-body");
    const modal = document.getElementById("req-repuestos-body");

    if (modal) modal.style.display = "none";
    if (main) main.style.display = "flex";
};


/* ============================================================
    LÓGICA COMPLETA DEL CARRITO DE REPUESTOS
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    let selectedItems = [];
    let cartItemCount = 0;

    const cartContainer  = document.getElementById('cart-container');
    const cartCountBadge = document.getElementById('cart-count-badge');

    // si el contenedor no existe, creamos uno para evitar errores
    if (!cartContainer) {
        console.warn('cart-container no encontrado en DOM');
    }

    /* ======================
        AGREGAR AL CARRITO
    ====================== */
    window.addToCart = function (cod, name, stock) {
        cod = String(cod);

        const found = selectedItems.find(i => i.cod === cod);

        if (found) {
            if (found.qty < found.stock) {
                found.qty++;
                const row = cartContainer && cartContainer.querySelector(`.cart-item[data-cod='${cod}']`);
                if (row) row.querySelector('.qty-val').textContent = found.qty;
            } else {
                // ya alcanzó el stock, no hacemos nada
                console.log('Llegaste al stock máximo para', name);
            }
            return;
        }

        const initialQty = stock > 0 ? 1 : 0;

        selectedItems.push({
            cod,
            name,
            qty: initialQty,
            stock: Number(stock)
        });

        const emptyState = cartContainer && cartContainer.querySelector('.empty-cart-state');
        if (emptyState) emptyState.remove();

        const row = document.createElement("div");
        row.className = "cart-item";
        row.dataset.cod = cod;
        row.innerHTML = `
            <div class="qty-control">
                <button class="qty-btn plus" onclick="updateQty(this, 1)" ${initialQty >= stock ? 'disabled' : ''}>+</button>
                <span class="qty-val">${initialQty}</span>
                <button class="qty-btn minus" onclick="updateQty(this, -1)" ${initialQty <= 1 ? 'disabled' : ''}>-</button>
            </div>
            <div class="item-text">${name}</div>
            <div class="stock-text">/ ${stock}</div>
            <button class="del-btn" onclick="removeItem(this)">✕</button>
        `;

        if (cartContainer) cartContainer.appendChild(row);

        cartItemCount++;
        updateCartCount();
    };

    /* ======================
        ACTUALIZAR CANTIDAD
    ====================== */
    window.updateQty = function (btn, change) {
        const row = btn.closest(".cart-item");
        if (!row) return;

        const cod = row.dataset.cod;
        const span = row.querySelector('.qty-val');
        const plusBtn = row.querySelector('.qty-btn.plus');
        const minusBtn = row.querySelector('.qty-btn.minus');

        const found = selectedItems.find(i => i.cod === String(cod));
        if (!found) return;

        let newQty = found.qty + change;
        if (newQty < 1) newQty = 1;
        if (newQty > found.stock) newQty = found.stock;

        found.qty = newQty;
        if (span) span.textContent = newQty;

        // desactivar/activar botones según límites
        if (plusBtn) plusBtn.disabled = found.qty >= found.stock;
        if (minusBtn) minusBtn.disabled = found.qty <= 1;
    };


    /* ======================
        ELIMINAR ITEM DEL CARRITO
    ====================== */
    window.removeItem = function (btn) {
        const row = btn.closest(".cart-item");
        if (!row) return;

        const cod = row.dataset.cod;
        row.remove();

        selectedItems = selectedItems.filter(i => i.cod !== cod);

        cartItemCount = Math.max(0, cartItemCount - 1);
        updateCartCount();

        if (cartItemCount === 0 && cartContainer) {
            cartContainer.innerHTML = `
                <div class="empty-cart-state">
                    <div class="empty-cart-icon">
                        <span class="material-symbols-outlined">shopping_cart</span>
                    </div>
                    <p>Su lista está vacía</p>
                </div>
            `;
        }
    };

    /* ======================
        CONTADOR DEL CARRITO
    ====================== */
    function updateCartCount() {
        if (cartCountBadge) cartCountBadge.textContent = cartItemCount;
    }

    /* ============================================================
        ENVIAR SELECCIÓN A LA VISTA PRINCIPAL
    ============================================================= */
    window.sendSelection = function () {
        const list = document.querySelector(".repuestos-list");
        const form = document.getElementById("formSeguimiento");

        if (!list) {
            console.error("No se encontró .repuestos-list");
            return;
        }

        // Limpiar lista visual y inputs anteriores
        list.innerHTML = "";
        const oldInputs = document.querySelectorAll('input[name="repuestos[]"], input[name="qty[]"]');
        oldInputs.forEach(i => i.remove());

        selectedItems.forEach(item => {
            const uid = 'rep-' + item.cod;

            // Visual en la lista
            const div = document.createElement("div");
            div.className = "repuesto-item";
            div.dataset.uid = uid;
            div.dataset.cod = item.cod;
            div.innerHTML = `
                <span class="rep-name">${item.name}</span>
                <span class="rep-qty">(x${item.qty})</span>
                <button type="button" class="rep-remove" data-uid="${uid}" title="Eliminar" style="margin-left:8px;">🗑️</button>
            `;
            list.appendChild(div);

            // ✅ Input con el CÓDIGO del repuesto (no el nombre)
            if (form) {
                const inputCod = document.createElement("input");
                inputCod.type  = "hidden";
                inputCod.name  = "repuestos[]";
                inputCod.value = item.cod;   // ← cod_repuestos, no el nombre
                inputCod.dataset.uid = uid;
                form.appendChild(inputCod);

                const inputQty = document.createElement("input");
                inputQty.type  = "hidden";
                inputQty.name  = "qty[]";
                inputQty.value = item.qty;
                inputQty.dataset.uid = uid;
                form.appendChild(inputQty);
            }
        });

        // Delegación de eliminación en la lista principal
        list.querySelectorAll('.rep-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                const uid  = this.dataset.uid;
                const el   = list.querySelector('[data-uid="' + uid + '"]');
                if (el) el.remove();

                if (form) {
                    form.querySelectorAll('input[data-uid="' + uid + '"]')
                        .forEach(i => i.remove());
                }

                // También quitar del array selectedItems
                const cod = uid.replace('rep-', '');
                selectedItems = selectedItems.filter(i => i.cod !== cod);

                if (typeof actualizarEstadoBotonGuardar === 'function')
                    actualizarEstadoBotonGuardar();
            });
        });

        window.closeSeguimientoCategory();
    };


    /* ============================================================
        REMOVER ITEM DESDE LA LISTA FINAL
    ============================================================= */
    window.removeRepuestoFromList = function (ev, cod) {
        ev.preventDefault();

        const el = document.querySelector(`.repuesto-item[data-cod='${cod}']`);
        if (el) el.remove();

        selectedItems = selectedItems.filter(i => i.cod !== cod);
    };


    const detailHeader = document.querySelector('.detail-header');
    if (detailHeader) {
        try { detailHeader.onclick = null; } catch (e) {}
        detailHeader.addEventListener('click', (e) => {
            e.stopPropagation();
            window.backToCategories();
        });
    }

});
</script>

</div>