{{-- resources/views/gerente/detallesReportes.blade.php --}}
@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Detalle del Reporte')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">DETALLE DEL REPORTE</h1>
</div>

<div class="rp-page">

    <!-- ENCABEZADO -->
    <div class="rp-detail-header">
        <h2 class="rp-detail-date">
            {{-- Mostrar fecha del mantenimiento (día legible, con inicial mayúscula) --}}
            {{ \Carbon\Carbon::parse($mantenimiento->fen_man ?? now())->locale('es')->translatedFormat('l - d/m/Y') }}
            <span class="rp-time-badge">
                {{-- Hora del primer seguimiento (si existe) o guion --}}
                {{ optional($seguimientos->first())->fcs_seg ? \Carbon\Carbon::parse($seguimientos->first()->fcs_seg)->format('H:i') : '—' }}
            </span>
        </h2>

        <a href="{{ route('gerente.tecnico.reportes', $tecnico->cod_tau) }}" class="rp-back-btn" title="Volver">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    <!-- GRID PRINCIPAL -->
    <div class="rp-detail-grid">

        <!-- TARJETA RESUMEN (arriba) -->
        <div class="rp-card rp-full-width">
            <div class="rp-card-accent"></div>

            <div class="rp-info-grid">

                <div class="rp-info-item">
                    <span class="rp-label">Mecánico</span>
                    <span class="rp-value">{{ $tecnico->usuario->registro->nom_reg ?? '—' }}</span>
                </div>

                <div class="rp-info-item">
                    <span class="rp-label">Placa</span>
                    <span class="rp-badge-orange">{{ $mantenimiento->vehiculo->pla_veh ?? '—' }}</span>
                </div>

                <div class="rp-info-item">
                    <span class="rp-label">Entrada</span>
                    <span class="rp-value">{{ \Carbon\Carbon::parse($mantenimiento->fen_man ?? now())->format('d/m/Y') }}</span>
                </div>

                <div class="rp-info-item">
                    <span class="rp-label">Salida Aprox.</span>
                    <span class="rp-value">{{ $mantenimiento->ffi_man ?? '—' }}</span>
                </div>

                <div class="rp-info-item">
                    <span class="rp-label">Marca</span>
                    <span class="rp-value">{{ $mantenimiento->vehiculo->mar_veh ?? '—' }}</span>
                </div>

                <div class="rp-info-item">
                    <span class="rp-label">Modelo</span>
                    <span class="rp-value">{{ $mantenimiento->vehiculo->mod_veh ?? '—' }}</span>
                </div>

            </div>
        </div>

        <!-- LISTA DE SEGUIMIENTOS (IZQ) -->
        <div class="rp-card rp-obs-box" style="grid-column: 1 / 2;">
            <div class="rp-card-accent"></div>

            <h4 class="rp-box-title"><i class="fa-solid fa-list"></i> Seguimientos</h4>

            @if($seguimientos->isEmpty())
                <p style="opacity:.7">No hay seguimientos registrados para este mantenimiento.</p>
            @else
                {{-- Agrupar por fecha legible (ya deberías haberlo calculado en el controlador; si no, lo calculamos aquí) --}}
                @php
                    $groupedSeguimientos = $groupedSeguimientos ?? $seguimientos->groupBy(function($s){
                        $d = \Carbon\Carbon::parse($s->fcs_seg)->locale('es')->translatedFormat('l - d/m/Y');
                        $first = mb_strtoupper(mb_substr($d, 0, 1, 'UTF-8'), 'UTF-8');
                        $rest  = mb_substr($d, 1, null, 'UTF-8');
                        return $first . $rest;
                    });
                @endphp

                @foreach($groupedSeguimientos as $dateLabel => $segs)
                    <div class="rt-date-section" style="margin-bottom:14px;">
                        <h5 class="rt-date-title" style="font-size:14px; margin-bottom:8px;">{{ $dateLabel }}</h5>

                        <div class="rt-cards-row">
                            @foreach($segs as $s)
                                @php
                                    // preparar datos para data-attrs
                                    $parts = $s->parts ?? []; // si tu modelo tiene repuestos en relación 'parts' o ajusta
                                    $partsJson = json_encode($parts, JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT);
                                    $obsEsc = e($s->obs_seg ?? '—');
                                @endphp

                                <div
                                    class="rt-card"
                                    role="button"
                                    tabindex="0"
                                    data-cod_seg="{{ $s->cod_seg }}"
                                    data-fcs="{{ $s->fcs_seg }}"  {{-- timestamp original (usado para comparar cual es mas reciente) --}}
                                    data-placa="{{ $mantenimiento->vehiculo->pla_veh ?? '' }}"
                                    data-marca="{{ $mantenimiento->vehiculo->mar_veh ?? '' }}"
                                    data-modelo="{{ $mantenimiento->vehiculo->mod_veh ?? '' }}"
                                    data-entrada="{{ \Carbon\Carbon::parse($s->fcs_seg)->format('d/m/Y') }}"
                                    data-salida="{{ $s->fecha_salida ?? '' }}"
                                    data-hora="{{ \Carbon\Carbon::parse($s->fcs_seg)->format('H:i') }}"
                                    data-obs="{{ $obsEsc }}"
                                    data-parts='{!! $partsJson !!}'
                                >
                                    <div class="rt-card-left">
                                        <div class="rt-file-icon"><i class="fa-regular fa-file-lines"></i></div>
                                        <div class="rt-vertical"></div>
                                    </div>

                                    <span class="rt-card-code">{{ $mantenimiento->vehiculo->pla_veh ?? '—' }}</span>
                                    <span class="rt-card-time">{{ \Carbon\Carbon::parse($s->fcs_seg)->format('H:i') }}</span>
                                    <i class="fa-regular fa-clock rt-status rt-spin-slow" aria-hidden="true"></i>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- PANEL DERECHO: Observaciones y Repuestos del seguimiento seleccionado -->
        <div class="rp-card rp-parts-box" style="grid-column: 2 / -1;">
            <div class="rp-card-accent"></div>

            <h4 class="rp-box-title"><i class="fa-solid fa-comment-dots"></i> Observaciones</h4>
            <p id="detalle-obs" class="rp-obs-text">
                {{-- Mostrar observaciones del primer seguimiento si existe, sino guion --}}
                {{ $seguimientos->first()->obs_seg ?? '—' }}
            </p>

            <h4 class="rp-box-title" style="margin-top:20px;"><i class="fa-solid fa-wrench"></i> Repuestos requeridos</h4>

            <ul id="detalle-parts" class="rp-parts-list">
                @php
                    $first = $seguimientos->first();
                    $firstParts = $first && isset($first->parts) ? $first->parts : [];
                @endphp

                @forelse($firstParts as $p)
                    <li>
                        <span class="rp-qty">{{ $p['qty'] ?? $p->cantidad ?? 1 }}</span>
                        <span class="rp-part-name">{{ $p['name'] ?? $p['nombre'] ?? ($p->nombre ?? '-') }}</span>
                    </li>
                @empty
                    <li style="opacity:.6">No hay repuestos registrados</li>
                @endforelse
            </ul>
        </div>

    </div> <!-- .rp-detail-grid -->

{{-- 
    <!-- FOOTER -->
    <div class="rp-detail-footer">

        <div class="rp-checkbox-wrapper">
            <input type="checkbox" id="rp-cb-19" />
            <label for="rp-cb-19" class="rp-check-box"></label>
            <span class="rp-label-text">Confirmar repuestos y avance del mantenimiento</span>
        </div>

        <input type="hidden" id="cod-seg-selected" name="cod_seg_selected" value="{{ $seguimientos->first()->cod_seg ?? '' }}">

        <button id="rp-send-btn" class="rp-send-btn" disabled>
            <span>Enviar Reporte</span>
            <i class="fa-solid fa-paper-plane"></i>
        </button>

    </div>
--}}

</div> <!-- .rp-page -->

{{-- ===== JS: carga dinámica y control de botón ===== --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = Array.from(document.querySelectorAll('.rt-card'));
    const detalleObs = document.getElementById('detalle-obs');
    const detalleParts = document.getElementById('detalle-parts');
    const rpTimeBadge = document.querySelector('.rp-time-badge');
    const codHidden = document.getElementById('cod-seg-selected');
    const sendBtn = document.getElementById('rp-send-btn');

    // si no hay tarjetas, desactivar botón
    if (!cards.length) {
        if (sendBtn) sendBtn.disabled = true;
        return;
    }

    // normalize/parse fcs: reemplaza espacio por T para parseo
    function parseFcs(card) {
        let raw = card.dataset.fcs || '';
        if (!raw) return NaN;
        raw = raw.replace(' ', 'T');
        const t = Date.parse(raw);
        return isNaN(t) ? NaN : t;
    }

    // buscar el seguimiento más reciente (mayor timestamp)
    let latestTime = -Infinity;
    let latestCod = null;
    cards.forEach(c => {
        const t = parseFcs(c);
        if (!isNaN(t) && t > latestTime) {
            latestTime = t;
            latestCod = c.dataset.cod_seg;
        }
    });

    // render parts (seguro, escapando)
    function renderParts(parts) {
        if (!Array.isArray(parts) || parts.length === 0) {
            return '<li style="opacity:.6">No hay repuestos registrados</li>';
        }
        return parts.map(p => {
            const qty = p.qty ?? p.cantidad ?? 1;
            const name = p.name ?? p.nombre ?? (typeof p === 'string' ? p : '-');
            const escName = String(name).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
            return `<li>
                        <span class="rp-qty">${qty}</span>
                        <span class="rp-part-name">${escName}</span>
                    </li>`;
        }).join('');
    }

    // activar una tarjeta: actualizar obs/parts/hora/hidden/estado boton
    function activateCard(card) {
        const obs = card.dataset.obs || '—';
        const partsRaw = card.dataset.parts || '[]';
        let parts = [];
        try { parts = JSON.parse(partsRaw); } catch(e){ parts = []; }

        if (detalleObs) detalleObs.textContent = obs;
        if (detalleParts) detalleParts.innerHTML = renderParts(parts);

        // actualizar hora en badge (opcional)
        if (rpTimeBadge) rpTimeBadge.textContent = card.dataset.hora ?? '—';

        // marcar activo visualmente
        cards.forEach(c => c.classList.remove('rt-card-active'));
        card.classList.add('rt-card-active');

        // guardar cod seleccionado
        if (codHidden) codHidden.value = card.dataset.cod_seg ?? '';

        // habilitar boton solo si es el más reciente
        const selectedCod = String(card.dataset.cod_seg);
        const isLatest = String(selectedCod) === String(latestCod);
        if (sendBtn) {
            sendBtn.disabled = !isLatest;
            sendBtn.title = isLatest ? "Enviar reporte (seguimiento más reciente)" : "Solo puede enviar desde el seguimiento más reciente";
        }
    }

    // añadir evento click a tarjetas
    cards.forEach(card => {
        card.addEventListener('click', function () {
            activateCard(card);
        });
    });

    // seleccionar por defecto la tarjeta más reciente si existe, sino la primera
    let defaultCard = cards.find(c => String(c.dataset.cod_seg) === String(latestCod)) || cards[0];
    activateCard(defaultCard);

    // Envío del reporte: ejemplo con fetch POST JSON (ajusta ruta en backend)
    if (sendBtn) {
        sendBtn.addEventListener('click', function (e) {
            if (sendBtn.disabled) { e.preventDefault(); return; }
            const selectedCod = codHidden ? codHidden.value : null;
            if (!selectedCod) {
                alert('No hay seguimiento seleccionado para enviar.');
                return;
            }

            // CSRF token (si tu layout incluye meta csrf-token)
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            // Cambia esta ruta por la real que procese el envio en tu controlador
            const url = "{{ route('gerente.reporte.enviar') }}"; // <-- ajustar si es necesario

            // Ejemplo simple POST JSON
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ cod_seg: selectedCod })
            }).then(resp => {
                if (!resp.ok) throw new Error('Error en el servidor');
                return resp.json();
            }).then(json => {
                // Manejo de respuesta: muestra mensaje o redirige
                alert(json.message ?? 'Reporte enviado correctamente');
                location.reload();
            }).catch(err => {
                console.error(err);
                alert('Error al enviar el reporte. Revisa la consola.');
            });
        });
    }
});
</script>

@push('style')
    @vite('resources/css/detallesReportes.css')
@endpush

@endsection
