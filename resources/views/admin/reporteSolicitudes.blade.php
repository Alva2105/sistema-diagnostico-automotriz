@extends('layouts.dashboard')

@section('contenido')

@php
    $total       = $solicitudes->count();
    $pendientes  = $solicitudes->where('est_sol', 'Pendiente')->count();
    $enProceso   = $solicitudes->where('est_sol', 'En_Proceso')->count();
    $finalizados = $solicitudes->where('est_sol', 'Finalizado')->count();
    $cancelados  = $solicitudes->where('est_sol', 'Cancelado')->count();
@endphp

<div class="reporte-content">

    {{-- ── HEADER ── --}}
    <div class="reporte-header no-print">
        <h2>
            <span class="material-symbols-outlined" style="vertical-align:middle;">summarize</span>
            Reporte de Solicitudes
        </h2>
        <div style="display:flex; gap:10px;">
            <button class="btn-excel" onclick="exportarExcel()">
                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">table_view</span>
                Descargar Excel
            </button>
            <button class="btn-imprimir" onclick="window.print()">
                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">print</span>
                Imprimir / Exportar PDF
            </button>
        </div>
    </div>

    {{-- ── FILA PRINCIPAL: gráficos | filtros ── --}}
    <div class="reporte-top-layout no-print">

        {{-- Gráficos lado a lado, centrados --}}
        <div class="graficos-panel">
            <div class="grafico-card">
                <p class="grafico-titulo">Por estado</p>
                <canvas id="graficaEstado"></canvas>
            </div>
            <div class="grafico-card">
                <p class="grafico-titulo">Por tipo</p>
                <canvas id="graficaTipo"></canvas>
            </div>
        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('solicitudes.reporte') }}"
              class="filtros-reporte">

            <div class="filtro-grupo">
                <label>Estado</label>
                <select name="estado">
                    <option value="">Todos</option>
                    <option value="Pendiente"  {{ request('estado') === 'Pendiente'  ? 'selected' : '' }}>Pendiente</option>
                    <option value="En_Proceso" {{ request('estado') === 'En_Proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="Finalizado" {{ request('estado') === 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                    <option value="Cancelado"  {{ request('estado') === 'Cancelado'  ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>

            <div class="filtro-grupo">
                <label>Tipo</label>
                <select name="tipo">
                    <option value="">Todos</option>
                    <option value="Preventivo" {{ request('tipo') === 'Preventivo' ? 'selected' : '' }}>Preventivo</option>
                    <option value="Correctivo" {{ request('tipo') === 'Correctivo' ? 'selected' : '' }}>Correctivo</option>
                </select>
            </div>

            <div class="filtro-grupo">
                <label>Cliente</label>
                <input type="text" name="cliente"
                       value="{{ request('cliente') }}"
                       placeholder="Nombre del cliente...">
            </div>

            <div class="filtro-grupo">
                <label>Desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}">
            </div>

            <div class="filtro-grupo">
                <label>Hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}">
            </div>

            <div class="filtro-grupo" style="align-self:flex-end;">
                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="{{ route('solicitudes.reporte') }}" class="btn-limpiar">Limpiar</a>
            </div>

        </form>

    </div>{{-- /reporte-top-layout --}}

    {{-- ── ENCABEZADO SOLO PARA IMPRESIÓN ── --}}
    <div class="reporte-print-header solo-print">
        <h2>JHIRE Motors — Reporte de Solicitudes</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
        <div class="filtros-aplicados">
            @if(request('estado')) <span>Estado: {{ request('estado') }}</span> @endif
            @if(request('tipo'))   <span>Tipo: {{ request('tipo') }}</span>     @endif
            @if(request('cliente'))<span>Cliente: {{ request('cliente') }}</span>@endif
            @if(request('desde'))  <span>Desde: {{ request('desde') }}</span>   @endif
            @if(request('hasta'))  <span>Hasta: {{ request('hasta') }}</span>   @endif
        </div>
    </div>

    {{-- ── TARJETAS DE TOTALES ── --}}
    <div class="resumen-cards">
        <div class="resumen-card total">
            <span class="resumen-num">{{ $total }}</span>
            <span class="resumen-label">Total</span>
        </div>
        <div class="resumen-card pendiente">
            <span class="resumen-num">{{ $pendientes }}</span>
            <span class="resumen-label">Pendientes</span>
        </div>
        <div class="resumen-card en-proceso">
            <span class="resumen-num">{{ $enProceso }}</span>
            <span class="resumen-label">En Proceso</span>
        </div>
        <div class="resumen-card finalizado">
            <span class="resumen-num">{{ $finalizados }}</span>
            <span class="resumen-label">Finalizados</span>
        </div>
        <div class="resumen-card cancelado">
            <span class="resumen-num">{{ $cancelados }}</span>
            <span class="resumen-label">Cancelados</span>
        </div>
    </div>

    {{-- ── TABLA ── --}}
    <div class="table-wrapper">
        <table id="tablaReporte">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Tipo</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>F. Programada</th>
                    <th>Observación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($solicitudes as $s)
                    <tr>
                        <td>{{ $s->cod_solicitudes }}</td>
                        <td>{{ $s->cliente?->nom_cli }} {{ $s->cliente?->app_cli }}</td>
                        <td>{{ $s->vehiculo?->mar_veh }} {{ $s->vehiculo?->mod_veh }}</td>
                        <td>{{ $s->tma_sol }}</td>
                        <td>{{ $s->ser_sol ?? '—' }}</td>
                        <td>
                            <span class="estado {{ strtolower(str_replace([' ','_'], '-', $s->est_sol)) }}">
                                {{ $s->est_sol }}
                            </span>
                        </td>
                        <td>{{ $s->fec_sol ? \Carbon\Carbon::parse($s->fec_sol)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $s->fpr_sol ? \Carbon\Carbon::parse($s->fpr_sol)->format('d/m/Y') : '—' }}</td>
                        <td>{{ $s->obs_sol ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center; color:#aaa; padding:30px;">
                            No hay resultados con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="reporte-footer solo-print">
        Total de registros: <strong>{{ $total }}</strong>
    </div>

</div>

<style>
    .reporte-content { padding-bottom: 40px; }

    /* ── Header ── */
    .reporte-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .reporte-header h2 {
        color: #ff7b00;
        font-size: 20px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-imprimir {
        background: #ff7b00; color: #fff; border: none;
        padding: 9px 20px; border-radius: 6px; cursor: pointer;
        font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all 0.2s;
    }
    .btn-imprimir:hover { background: #e06a00; }

    /* ── Fila principal: gráficos + filtros ── */
    .reporte-top-layout {
        display: flex;
        flex-direction: row;        /* ← fila horizontal */
        gap: 20px;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    /* ── Panel de gráficos (izquierda) ── */
    .graficos-panel {
        display: flex;
        flex-direction: row;        /* ← los dos gráficos lado a lado */
        gap: 14px;
        flex: 1;                    /* ← ocupa la mitad disponible */
        min-width: 0;
    }
    .grafico-card {
        flex: 1;
        min-width: 0;
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 10px;
        padding: 14px 16px;
    }
    .grafico-titulo {
        color: #aaa;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 0 10px;
    }

    /* ── Filtros (derecha) ── */
    .filtros-reporte {
        flex: 1;                    /* ← ocupa la otra mitad */
        display: flex;
        flex-direction: column;     /* ← filtros en columna vertical */
        gap: 12px;
        background: #2a2a2a;
        border: 1px solid #3a3a3a;
        border-radius: 10px;
        padding: 18px 20px;
        box-sizing: border-box;
        margin-bottom: 0;
    }
    .filtro-grupo {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filtro-grupo label { color: #ccc; font-size: 12px; font-weight: 500; }
    .filtro-grupo input,
    .filtro-grupo select {
        width: 100%;
        padding: 7px 10px;
        background: #3a3a3a;
        border: 1px solid #555;
        border-radius: 6px;
        color: #fff;
        font-size: 13px;
        box-sizing: border-box;
    }
    .btn-filtrar {
        background: #ff7b00; color: #fff; border: none;
        padding: 9px 20px; border-radius: 6px;
        cursor: pointer; font-weight: 600; font-size: 13px;
        width: 100%; transition: all 0.2s;
    }
    .btn-filtrar:hover { background: #e06a00; }
    .btn-limpiar {
        color: #aaa; font-size: 13px; text-decoration: none;
        text-align: center; display: block;
        padding: 4px 0; transition: color 0.2s;
    }
    .btn-limpiar:hover { color: #fff; }

    /* ── Tarjetas totales ── */
    .resumen-cards {
        display: flex;
        gap: 12px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }
    .resumen-card {
        flex: 1; min-width: 100px;
        background: #2a2a2a; border-radius: 10px;
        padding: 16px; text-align: center;
        border-top: 3px solid #444;
    }
    .resumen-card.total      { border-top-color: #ff7b00; }
    .resumen-card.pendiente  { border-top-color: #6c757d; }
    .resumen-card.en-proceso { border-top-color: #ffc107; }
    .resumen-card.finalizado { border-top-color: #28a745; }
    .resumen-card.cancelado  { border-top-color: #dc3545; }
    .resumen-num   { display:block; font-size:28px; font-weight:700; color:#fff; }
    .resumen-label { display:block; font-size:12px; color:#aaa; margin-top:4px; }

    /* ── Estados ── */
    .estado { padding:3px 8px; border-radius:8px; font-size:11px; font-weight:600; }
    .estado.pendiente  { background:#6c757d; color:#fff; }
    .estado.en-proceso { background:#ffc107; color:#000; }
    .estado.finalizado { background:#28a745; color:#fff; }
    .estado.cancelado  { background:#dc3545; color:#fff; }

    /* ── Solo impresión ── */
    .solo-print { display: none; }
    .reporte-print-header { margin-bottom: 20px; }
    .reporte-print-header h2 { font-size: 18px; margin-bottom: 4px; }
    .reporte-print-header p  { font-size: 12px; color: #555; }
    .filtros-aplicados { display:flex; gap:12px; flex-wrap:wrap; margin-top:6px; font-size:11px; }
    .filtros-aplicados span { background:#eee; padding:2px 8px; border-radius:4px; color:#333; }
    .reporte-footer { margin-top:16px; font-size:12px; text-align:right; }

    /* ════════════════════════════════════
       IMPRESIÓN
    ════════════════════════════════════ */
    @media print {
        body * { visibility: hidden; }
        .reporte-content, .reporte-content * { visibility: visible; }
        .reporte-content { position: absolute; top: 0; left: 0; width: 100%; }
        .no-print  { display: none !important; }
        .solo-print{ display: block !important; }
        table { border-collapse: collapse; width: 100%; font-size: 11px; }
        th, td { border: 1px solid #999; padding: 5px 8px; color: #000 !important; }
        th { background: #f0f0f0 !important; font-weight: bold; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .estado { border:1px solid #999 !important; background:transparent !important;
                  color:#000 !important; padding:1px 4px; }
        .resumen-cards { display: flex !important; }
        .resumen-card  { border:1px solid #ccc; background:#fff; }
        .resumen-num   { color:#000 !important; }
        .resumen-label { color:#555 !important; }
    }

    .btn-excel {
        background: #1a6b3c;
        color: #fff;
        border: none;
        padding: 9px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-excel:hover { background: #157a36; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
Chart.defaults.color        = '#aaa';
Chart.defaults.borderColor  = '#3a3a3a';

const datosEstado = {
    labels: ['Pendiente', 'En Proceso', 'Finalizado', 'Cancelado'],
    datasets: [{
        data: [{{ $pendientes }}, {{ $enProceso }}, {{ $finalizados }}, {{ $cancelados }}],
        backgroundColor: ['#6c757d', '#ffc107', '#28a745', '#dc3545'],
        borderWidth: 0,
        hoverOffset: 6
    }]
};

const datosTipo = {
    labels: ['Preventivo', 'Correctivo'],
    datasets: [{
        data: [
            {{ $solicitudes->filter(fn($s) => str_contains(strtolower($s->tma_sol), 'preventivo'))->count() }},
            {{ $solicitudes->filter(fn($s) => str_contains(strtolower($s->tma_sol), 'correctivo'))->count() }}
        ],
        backgroundColor: ['#ff7b00', '#3a8fd4'],
        borderRadius: 6,
        borderWidth: 0
    }]
};

new Chart(document.getElementById('graficaEstado'), {
    type: 'doughnut',
    data: datosEstado,
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 10, padding: 10, font: { size: 11 } }
            },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` }
            }
        }
    }
});

new Chart(document.getElementById('graficaTipo'), {
    type: 'bar',
    data: datosTipo,
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: { label: ctx => ` ${ctx.raw} solicitudes` }
            }
        },
        scales: {
            x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#3a3a3a' } },
            y: { grid: { display: false } }
        }
    }
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
function exportarExcel() {
    const tabla = document.getElementById('tablaReporte');

    const clon = tabla.cloneNode(true);
    clon.querySelectorAll('span.estado').forEach(span => {
        span.replaceWith(document.createTextNode(span.textContent.trim()));
    });

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.table_to_sheet(clon, { raw: false });

    ws['!cols'] = [
        { wch: 10 },  // ID
        { wch: 25 },  // Cliente
        { wch: 22 },  // Vehículo
        { wch: 28 },  // Tipo
        { wch: 25 },  // Servicio
        { wch: 14 },  // Estado
        { wch: 14 },  // Fecha
        { wch: 14 },  // F. Programada
        { wch: 35 },  // Observación
    ];

    XLSX.utils.book_append_sheet(wb, ws, 'Solicitudes');

    const hoy = new Date().toISOString().split('T')[0];
    XLSX.writeFile(wb, `Reporte_Solicitudes_${hoy}.xlsx`);
}
</script>

@endsection