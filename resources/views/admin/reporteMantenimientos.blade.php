@extends('layouts.dashboard')

@section('contenido')

@php
    $total      = $mantenimientos->count();
    $totalBs    = $mantenimientos->sum('total_man');
    $promedioBs = $total > 0 ? $totalBs / $total : 0;
    $preventivos= $mantenimientos->filter(fn($m) => str_contains(strtolower($m->solicitud?->tma_sol ?? ''), 'preventivo'))->count();
    $correctivos= $mantenimientos->filter(fn($m) => str_contains(strtolower($m->solicitud?->tma_sol ?? ''), 'correctivo'))->count();

    // Agrupar por mes para la gráfica
    $porMes = $mantenimientos
        ->filter(fn($m) => $m->fec_fin_man)
        ->groupBy(fn($m) => $m->fec_fin_man->format('Y-m'))
        ->map(fn($g) => $g->count())
        ->sortKeys();

    $mesesLabels = $porMes->keys()->map(fn($k) =>
        \Carbon\Carbon::createFromFormat('Y-m', $k)->translatedFormat('M Y')
    )->values()->toJson();

    $mesesData = $porMes->values()->toJson();
@endphp

<div class="reporte-content">

    {{-- ── HEADER ── --}}
    <div class="reporte-header no-print">
        <h2>
            <span class="material-symbols-outlined" style="vertical-align:middle;">build_circle</span>
            Reporte de Mantenimientos
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

        {{-- Gráficos lado a lado --}}
        <div class="graficos-panel">

            {{-- Barras por mes --}}
            <div class="grafico-card">
                <p class="grafico-titulo">Finalizados por mes</p>
                <canvas id="graficaMes"></canvas>
            </div>

            {{-- Barras por tipo --}}
            <div class="grafico-card">
                <p class="grafico-titulo">Por tipo</p>
                <canvas id="graficaTipo"></canvas>
            </div>

        </div>

        {{-- Filtros --}}
        <form method="GET" action="{{ route('mantenimientos.reporte') }}"
              class="filtros-reporte">

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
                <label>Desde (F. fin)</label>
                <input type="date" name="desde" value="{{ request('desde') }}">
            </div>

            <div class="filtro-grupo">
                <label>Hasta (F. fin)</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}">
            </div>

            <div class="filtro-grupo">
                <button type="submit" class="btn-filtrar">Filtrar</button>
                <a href="{{ route('mantenimientos.reporte') }}" class="btn-limpiar">Limpiar</a>
            </div>

        </form>

    </div>{{-- /reporte-top-layout --}}

    {{-- ── ENCABEZADO SOLO IMPRESIÓN ── --}}
    <div class="reporte-print-header solo-print">
        <h2>JHIRE Motors — Reporte de Mantenimientos</h2>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
        <div class="filtros-aplicados">
            @if(request('tipo'))    <span>Tipo: {{ request('tipo') }}</span>       @endif
            @if(request('cliente')) <span>Cliente: {{ request('cliente') }}</span> @endif
            @if(request('desde'))   <span>Desde: {{ request('desde') }}</span>     @endif
            @if(request('hasta'))   <span>Hasta: {{ request('hasta') }}</span>     @endif
        </div>
    </div>

    {{-- ── TARJETAS DE TOTALES ── --}}
    <div class="resumen-cards">
        <div class="resumen-card total">
            <span class="resumen-num">{{ $total }}</span>
            <span class="resumen-label">Total registros</span>
        </div>
        <div class="resumen-card preventivo">
            <span class="resumen-num">{{ $preventivos }}</span>
            <span class="resumen-label">Preventivos</span>
        </div>
        <div class="resumen-card correctivo">
            <span class="resumen-num">{{ $correctivos }}</span>
            <span class="resumen-label">Correctivos</span>
        </div>
        <div class="resumen-card monto">
            <span class="resumen-num">{{ number_format($totalBs, 2) }}</span>
            <span class="resumen-label">Total Bs.</span>
        </div>
        <div class="resumen-card promedio">
            <span class="resumen-num">{{ number_format($promedioBs, 2) }}</span>
            <span class="resumen-label">Promedio Bs.</span>
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
                    <th>Descripción</th>
                    <th>F. Inicio</th>
                    <th>F. Fin</th>
                    <th>Total (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mantenimientos as $m)
                    @php
                        $cliente  = $m->solicitud?->cliente;
                        $vehiculo = $m->solicitud?->vehiculo;
                        $tipo     = $m->solicitud?->tma_sol ?? '—';
                    @endphp
                    <tr>
                        <td>{{ $m->cod_mantenimientos }}</td>
                        <td>{{ $cliente ? $cliente->nom_cli.' '.$cliente->app_cli : '—' }}</td>
                        <td>{{ $vehiculo ? $vehiculo->mar_veh.' '.$vehiculo->mod_veh : '—' }}</td>
                        <td>
                            <span class="badge-tipo {{ str_contains(strtolower($tipo), 'correctivo') ? 'correctivo' : 'preventivo' }}">
                                {{ $tipo }}
                            </span>
                        </td>
                        <td class="desc-col">{{ $m->des_man ?? '—' }}</td>
                        <td>{{ $m->fec_ini_man ? $m->fec_ini_man->format('d/m/Y') : '—' }}</td>
                        <td>{{ $m->fec_fin_man ? $m->fec_fin_man->format('d/m/Y') : '—' }}</td>
                        <td style="font-weight:600;">{{ number_format($m->total_man ?? 0, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; color:#aaa; padding:30px;">
                            No hay resultados con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="reporte-footer solo-print">
        Total de registros: <strong>{{ $total }}</strong> —
        Total facturado: <strong>Bs. {{ number_format($totalBs, 2) }}</strong>
    </div>

</div>

<style>
    .reporte-content { padding-bottom: 40px; }

    /* ── Header ── */
    .reporte-header {
        display: flex; justify-content: space-between;
        align-items: center; margin-bottom: 20px;
    }
    .reporte-header h2 {
        color: #ff7b00; font-size: 20px; margin: 0;
        display: flex; align-items: center; gap: 8px;
    }
    .btn-imprimir {
        background: #ff7b00; color: #fff; border: none;
        padding: 9px 20px; border-radius: 6px; cursor: pointer;
        font-size: 13px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 6px;
        transition: all 0.2s;
    }
    .btn-imprimir:hover { background: #e06a00; }

    /* ── Fila principal ── */
    .reporte-top-layout {
        display: flex; flex-direction: row;
        gap: 20px; align-items: flex-start;
        margin-bottom: 24px;
    }

    /* ── Gráficos ── */
    .graficos-panel {
        display: flex; flex-direction: row;
        gap: 14px; flex: 1; min-width: 0;
    }
    .grafico-card {
        flex: 1; min-width: 0;
        background: #2a2a2a; border: 1px solid #3a3a3a;
        border-radius: 10px; padding: 14px 16px;
    }
    .grafico-titulo {
        color: #aaa; font-size: 12px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 10px;
    }

    /* ── Filtros ── */
    .filtros-reporte {
        flex: 1; display: flex; flex-direction: column; gap: 12px;
        background: #2a2a2a; border: 1px solid #3a3a3a;
        border-radius: 10px; padding: 18px 20px;
        box-sizing: border-box; margin-bottom: 0;
    }
    .filtro-grupo { display: flex; flex-direction: column; gap: 5px; }
    .filtro-grupo label { color: #ccc; font-size: 12px; font-weight: 500; }
    .filtro-grupo input,
    .filtro-grupo select {
        width: 100%; padding: 7px 10px;
        background: #3a3a3a; border: 1px solid #555;
        border-radius: 6px; color: #fff; font-size: 13px;
        box-sizing: border-box;
    }
    .btn-filtrar {
        background: #ff7b00; color: #fff; border: none;
        padding: 9px 20px; border-radius: 6px; cursor: pointer;
        font-weight: 600; font-size: 13px; width: 100%; transition: all 0.2s;
    }
    .btn-filtrar:hover { background: #e06a00; }
    .btn-limpiar {
        color: #aaa; font-size: 13px; text-decoration: none;
        text-align: center; display: block; padding: 4px 0; transition: color 0.2s;
    }
    .btn-limpiar:hover { color: #fff; }

    /* ── Tarjetas totales ── */
    .resumen-cards { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .resumen-card {
        flex: 1; min-width: 120px;
        background: #2a2a2a; border-radius: 10px;
        padding: 16px; text-align: center; border-top: 3px solid #444;
    }
    .resumen-card.total      { border-top-color: #ff7b00; }
    .resumen-card.preventivo { border-top-color: #3a8fd4; }
    .resumen-card.correctivo { border-top-color: #9b59b6; }
    .resumen-card.monto      { border-top-color: #28a745; }
    .resumen-card.promedio   { border-top-color: #17a2b8; }
    .resumen-num   { display:block; font-size:24px; font-weight:700; color:#fff; }
    .resumen-label { display:block; font-size:12px; color:#aaa; margin-top:4px; }

    /* ── Badges tipo ── */
    .badge-tipo {
        padding: 3px 10px; border-radius: 8px; font-size: 12px; font-weight: 600;
        background: transparent; white-space: nowrap;
    }
    .badge-tipo.preventivo { border: 1px solid #3a8fd4; color: #3a8fd4; }
    .badge-tipo.correctivo { border: 1px solid #9b59b6; color: #9b59b6; }

    /* ── Descripción corta ── */
    .desc-col { max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

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
        .no-print   { display: none !important; }
        .solo-print { display: block !important; }
        table { border-collapse: collapse; width: 100%; font-size: 11px; }
        th, td { border: 1px solid #999; padding: 5px 8px; color: #000 !important; }
        th { background: #f0f0f0 !important; font-weight: bold; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .badge-tipo { border: 1px solid #999 !important; color: #000 !important; padding: 1px 4px; }
        .resumen-cards { display: flex !important; }
        .resumen-card  { border: 1px solid #ccc; background: #fff; }
        .resumen-num   { color: #000 !important; }
        .resumen-label { color: #555 !important; }
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
Chart.defaults.color       = '#aaa';
Chart.defaults.borderColor = '#3a3a3a';

/* ── Barras por mes ── */
new Chart(document.getElementById('graficaMes'), {
    type: 'bar',
    data: {
        labels: {!! $mesesLabels !!},
        datasets: [{
            label: 'Mantenimientos',
            data: {!! $mesesData !!},
            backgroundColor: '#ff7b00',
            borderRadius: 6,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.raw} mantenimientos` } }
        },
        scales: {
            x: { grid: { color: '#3a3a3a' } },
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#3a3a3a' } }
        }
    }
});

/* ── Barras por tipo ── */
new Chart(document.getElementById('graficaTipo'), {
    type: 'bar',
    data: {
        labels: ['Preventivo', 'Correctivo'],
        datasets: [{
            data: [{{ $preventivos }}, {{ $correctivos }}],
            backgroundColor: ['#3a8fd4', '#9b59b6'],
            borderRadius: 6,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ` ${ctx.raw} mantenimientos` } }
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

    // Clonar tabla para limpiar los badges y dejar texto plano
    const clon = tabla.cloneNode(true);
    clon.querySelectorAll('span.badge-tipo, span.estado').forEach(span => {
        span.replaceWith(document.createTextNode(span.textContent.trim()));
    });

    // Crear hoja desde la tabla limpia
    const wb  = XLSX.utils.book_new();
    const ws  = XLSX.utils.table_to_sheet(clon, { raw: false });

    // Ancho de columnas
    ws['!cols'] = [
        { wch: 12 },  // ID
        { wch: 25 },  // Cliente
        { wch: 22 },  // Vehículo
        { wch: 28 },  // Tipo
        { wch: 30 },  // Descripción
        { wch: 14 },  // F. Inicio
        { wch: 14 },  // F. Fin
        { wch: 14 },  // Total Bs.
    ];

    // Nombre de la hoja y filtros aplicados como subtítulo
    const filtros = [
        '{{ request('tipo')    ? 'Tipo: '.request('tipo')       : '' }}',
        '{{ request('cliente') ? 'Cliente: '.request('cliente') : '' }}',
        '{{ request('desde')   ? 'Desde: '.request('desde')     : '' }}',
        '{{ request('hasta')   ? 'Hasta: '.request('hasta')     : '' }}',
    ].filter(Boolean).join(' | ');

    XLSX.utils.book_append_sheet(wb, ws, 'Mantenimientos');

    // Nombre del archivo con fecha actual
    const hoy = new Date().toISOString().split('T')[0];
    XLSX.writeFile(wb, `Reporte_Mantenimientos_${hoy}.xlsx`);
}
</script>
@endsection