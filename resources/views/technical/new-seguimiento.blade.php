@extends('layouts.slidebarTecnico')

@section('title', 'Técnico | Seguimiento (nuevo)')

@section('content')

<div class="NuevoSeguimiento-container">
    <div class="INVheader">
        <h1 class="INVtitle">SEGUIMIENTO</h1>
    </div>

    <div class="content-Newseguimiento">
        <a href="{{ route('tecnico.seguimiento', $solicitud->cod_solicitudes) }}" class="btnVolverSeg">
            <span class="material-symbols-outlined" style="font-size:20px;vertical-align:middle;">arrow_back</span>
            Volver
        </a>

        {{-- ================= HEADER ================= --}}
        <div class="detail-row">
            <div class="detail-id">
                <h2>{{ $solicitud->vehiculo->pla_veh ?? '—' }}</h2>
            </div>
            <div class="detail-info1">
                <p>Marca: <strong>{{ strtoupper($solicitud->vehiculo->mar_veh ?? '—') }}</strong></p>
                <p>Modelo: <strong>{{ $solicitud->vehiculo->mod_veh ?? '—' }}</strong></p>
                <p>Tipo: <strong>{{ $solicitud->vehiculo->tip_veh ?? '—' }}</strong></p>
            </div>
            <div class="detail-info2">
                <p>Tipo: <strong>{{ $solicitud->tma_sol ?? '—' }}</strong></p>
                <p>Servicio: <strong>{{ $solicitud->ser_sol ?? '—' }}</strong></p>
                <p>Observación: <strong>{{ $solicitud->obs_sol ?? '—' }}</strong></p>
            </div>
            <div class="detail-info3">
                <p>Fecha seguimiento: <strong>{{ now()->format('d/m/Y H:i') }}</strong></p>
                <p>Fecha programada:
                    <strong>
                        {{ $solicitud->fpr_sol
                            ? \Carbon\Carbon::parse($solicitud->fpr_sol)->format('d/m/Y')
                            : 'Sin fecha' }}
                    </strong>
                </p>
            </div>
        </div>

        {{-- ================= BODY ================= --}}
        <div class="seguimiento-body-wrapper">
            <div id="main-body" class="detail-body">

                {{-- OBSERVACIONES --}}
                <div class="detail-box observations-box">
                    <label for="observaciones">Observaciones:</label>
                    <textarea id="observaciones"></textarea>
                </div>

                {{-- REPUESTOS --}}
                <div class="detail-box repuestos-box">
                    <label>Repuestos:
                        <button class="btn-add-repuesto" onclick="openRepuestosWindow()">
                            <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle;">add</span>
                            Añadir
                        </button>
                    </label>

                    <div class="repuestos-list">
                        {{-- Aquí aparecen los repuestos seleccionados dinámicamente --}}
                    </div>
                </div>

            </div>

            {{-- Ventana secundaria para seleccionar repuestos --}}
            <div id="req-repuestos-body" style="display:none;">
                @include('technical.Req-repuestos')
            </div>
        </div>


        {{-- =============== FORMULARIO INVISIBLE QUE ENVÍA LOS DATOS =============== --}}
        <form id="formSeguimiento" action="{{ route('tecnico.seguimiento.guardar') }}" method="POST">
            @csrf
            <input type="hidden" name="cod_sol"    value="{{ $solicitud->cod_solicitudes }}">
            <input type="hidden" name="obs_avance" id="obs_seg_input">
            {{-- Los repuestos se insertan aquí dinámicamente por JS --}}
            {{-- <input type="hidden" name="repuestos[]"> --}}
            {{-- <input type="hidden" name="qty[]">       --}}
        </form>

        <button id="btnSaveSeg" class="btnSaveSeg" onclick="guardarSeguimiento(event)">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle;">save</span>
            Guardar
        </button>
    </div>
</div>

<script>
function openRepuestosWindow() {
    const main = document.getElementById('main-body');
    const req = document.getElementById('req-repuestos-body');

    main.style.display = 'none';
    req.style.display = 'block';
    req.classList.remove('fade-leave');
    req.classList.add('fade-enter');
}

function closeRepuestosWindow() {
    const main = document.getElementById('main-body');
    const req = document.getElementById('req-repuestos-body');

    req.classList.remove('fade-enter');
    req.classList.add('fade-leave');

    setTimeout(() => {
        req.style.display = 'none';
        main.style.display = 'block';
        main.classList.add('fade-enter');
        main.classList.remove('fade-leave');
    }, 200);
}
/* --------------------------
  Helpers y validadores
---------------------------*/
function tieneObservaciones() {
    const obs = document.getElementById('observaciones');
    return obs && obs.value.trim().length > 0;
}

function hayRepuestosSeleccionados() {
    const cont = document.querySelector('.repuestos-list');
    if (!cont) return false;

    // 1) preferimos contar elementos con clase "repuesto-item"
    const repItems = cont.querySelectorAll('.repuesto-item');
    if (repItems.length > 0) return true;

    // 2) si no hay con esa clase, comprobar hijos directos (fallback)
    if (cont.children.length > 0) return true;

    return false;
}

function actualizarEstadoBotonGuardar() {
    const btn = document.getElementById('btnSaveSeg');
    if (!btn) return;

    const ok = tieneObservaciones() && hayRepuestosSeleccionados();

    btn.disabled = !ok;
    btn.style.opacity = ok ? "1" : "0.5";
    btn.style.cursor = ok ? "pointer" : "not-allowed";

    // debug
    console.log('[validacion] obs=', tieneObservaciones(), ' repuestos=', hayRepuestosSeleccionados(), ' boton=', !btn.disabled);
}

/* --------------------------
  Añadir repuesto y ocultos al form
---------------------------*/
function addRepuesto(name, qty = 1) {
    const list = document.querySelector('.repuestos-list');
    const form = document.getElementById('formSeguimiento');

    if (!list || !form) {
        console.warn('No existe .repuestos-list o #formSeguimiento');
        return;
    }

    // generar un id único (por si quieres eliminar luego)
    const uid = 'rep-' + Date.now() + '-' + Math.floor(Math.random()*9999);

    // elemento visual
    const item = document.createElement('div');
    item.className = 'repuesto-item';
    item.dataset.uid = uid;
    item.innerHTML = `
        <span class="rep-name">${escapeHtml(name)}</span>
        <span class="rep-qty">(${qty})</span>
        <button type="button" class="rep-remove" data-uid="${uid}" title="Eliminar" style="margin-left:8px;">🗑️</button>
    `;
    list.appendChild(item);

    // hidden inputs (name y qty) — se agregan al form
    const inputName = document.createElement('input');
    inputName.type = 'hidden';
    inputName.name = 'repuestos[]';
    inputName.value = name;
    inputName.dataset.uid = uid;
    form.appendChild(inputName);

    const inputQty = document.createElement('input');
    inputQty.type = 'hidden';
    inputQty.name = 'qty[]';
    inputQty.value = qty;
    inputQty.dataset.uid = uid;
    form.appendChild(inputQty);

    // click para eliminar
    item.querySelector('.rep-remove').addEventListener('click', function(e){
        const uid = this.dataset.uid;
        // eliminar visual
        const el = list.querySelector('[data-uid="'+uid+'"]');
        if (el) el.remove();
        // eliminar hidden inputs
        const hn = form.querySelectorAll('input[data-uid="'+uid+'"]');
        hn.forEach(i=>i.remove());
        // actualizar estado
        actualizarEstadoBotonGuardar();
    });

    // actualizar estado
    actualizarEstadoBotonGuardar();
}

// Helper escape
function escapeHtml(unsafe) {
    return (unsafe+'').replace(/[&<"'>]/g, function(m){
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m];
    });
}

/* --------------------------
  Observers y listeners
---------------------------*/
document.addEventListener('DOMContentLoaded', function(){
    // 1) listener textarea
    const obs = document.getElementById('observaciones');
    if (obs) obs.addEventListener('input', actualizarEstadoBotonGuardar);

    // 2) observer sobre la lista de repuestos (detecta cambios incluso si se añaden desde otro script)
    const cont = document.querySelector('.repuestos-list');
    if (cont) {
        const mo = new MutationObserver(function(mutations){
            // console.log('Mutation repuestos', mutations);
            actualizarEstadoBotonGuardar();
        });
        mo.observe(cont, { childList: true, subtree: false });
    }

    // 3) inicializa estado
    actualizarEstadoBotonGuardar();

    // Opcional: delegación para eliminar si algunos elementos ya creados no usan el handler addRepuesto
    document.querySelector('.repuestos-list')?.addEventListener('click', function(e){
        if (e.target.matches('.rep-remove')) {
            const uid = e.target.dataset.uid;
            // disparar la misma lógica de eliminación
            const item = this.querySelector('[data-uid="'+uid+'"]');
            if (item) item.remove();
            // quitar inputs asociados si existen
            const form = document.getElementById('formSeguimiento');
            if (form) {
                const inputs = form.querySelectorAll('input[data-uid="'+uid+'"]');
                inputs.forEach(i=>i.remove());
            }
            actualizarEstadoBotonGuardar();
        }
    });
});

/* --------------------------
  Envío del form
---------------------------*/
function guardarSeguimiento(e) {
    const btn = document.getElementById('btnSaveSeg');
    if (!btn) return;

    if (btn.disabled) {
        e?.preventDefault();
        console.warn('Boton guardado deshabilitado - condiciones no cumplidas');
        actualizarEstadoBotonGuardar();
        return false;
    }

    // asignar observaciones al input oculto
    document.getElementById('obs_seg_input').value = document.getElementById('observaciones').value.trim();

    // submit
    document.getElementById('formSeguimiento').submit();
}
</script>

@endsection