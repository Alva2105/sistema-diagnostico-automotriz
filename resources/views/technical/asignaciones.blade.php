@extends('layouts.slidebarTecnico')

@section('title', 'Técnico | Mis Asignaciones')

@section('content')

{{-- ══════════════════════════════════════════
     HEADER
══════════════════════════════════════════ --}}
<div class="INVheader">
    <h1 class="INVtitle">MIS ASIGNACIONES</h1>
    <p class="asi-subtitulo">Trabajos que tienes asignados para atender</p>
</div>

{{-- ══════════════════════════════════════════
     CONTADOR RÁPIDO
══════════════════════════════════════════ --}}
<div class="asi-stats">
    <div class="asi-stat-card">
        <span class="asi-stat-num">{{ $asignaciones->count() }}</span>
        <span class="asi-stat-label">Total asignadas</span>
    </div>
    <div class="asi-stat-card">
        <span class="asi-stat-num" style="color:#ffc107;">
            {{ $asignaciones->filter(fn($a) => ($a->solicitud->est_sol ?? '') === 'Pendiente')->count() }}
        </span>
        <span class="asi-stat-label">Pendientes</span>
    </div>
    <div class="asi-stat-card">
        <span class="asi-stat-num" style="color:#4fc3f7;">
            {{ $asignaciones->filter(fn($a) => ($a->solicitud->est_sol ?? '') === 'En_Proceso')->count() }}
        </span>
        <span class="asi-stat-label">En proceso</span>
    </div>
    <div class="asi-stat-card">
        <span class="asi-stat-num" style="color:#66bb6a;">
            {{ $asignaciones->filter(fn($a) => ($a->solicitud->est_sol ?? '') === 'Finalizado')->count() }}
        </span>
        <span class="asi-stat-label">Finalizadas</span>
    </div>
</div>

{{-- ══════════════════════════════════════════
     TARJETAS
══════════════════════════════════════════ --}}
<div class="asi-grid">

    @forelse ($asignaciones as $a)

        @php
            $sol     = $a->solicitud;
            $veh     = $sol->vehiculo  ?? null;
            $cli     = $sol->cliente   ?? null;
            $estado  = $sol->est_sol   ?? 'Pendiente';
            $fpr     = $sol->fpr_sol   ?? null;

            $estadoClass = match($estado) {
                'Pendiente'  => 'badge-pendiente',
                'En_Proceso' => 'badge-enproceso',
                'Finalizado' => 'badge-finalizado',
                'Cancelado'  => 'badge-cancelado',
                default      => 'badge-pendiente',
            };

            $estadoLabel = match($estado) {
                'En_Proceso' => 'En Proceso',
                default      => $estado,
            };

            $tipoClass = str_contains($sol->tma_sol ?? '', 'Preventivo')
                ? 'tipo-preventivo' : 'tipo-correctivo';
        @endphp

        <div class="asi-card">

            {{-- Ícono + tipo de mantenimiento --}}
            <div class="asi-card-top">
                <div class="asi-icon-wrap {{ $tipoClass }}">
                    <span class="material-symbols-outlined" style="font-size:28px;">
                        {{ str_contains($sol->tma_sol ?? '', 'Preventivo') ? 'shield_check' : 'build' }}
                    </span>
                </div>
                <div>
                    <span class="asi-tipo {{ $tipoClass }}-txt">
                        {{ $sol->tma_sol ?? '—' }}
                    </span>
                    <span class="asi-cod">{{ $sol->cod_solicitudes ?? '—' }}</span>
                </div>
                <span class="asi-badge {{ $estadoClass }}">{{ $estadoLabel }}</span>
            </div>

            <div class="asi-divider"></div>

            {{-- Datos --}}
            <div class="asi-card-body">

                {{-- Cliente --}}
                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">account_circle</span>
                    <div>
                        <p class="asi-row-label">Cliente</p>
                        <p class="asi-row-val">
                            @if($cli)
                                {{ $cli->nom_cli }} {{ $cli->app_cli }}
                            @else
                                Sin cliente
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Vehículo --}}
                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">directions_car</span>
                    <div>
                        <p class="asi-row-label">Vehículo</p>
                        <p class="asi-row-val">
                            @if($veh)
                                {{ $veh->mar_veh }} {{ $veh->mod_veh }}
                                @if($veh->ani_veh)({{ $veh->ani_veh }})@endif
                                — <strong>{{ $veh->pla_veh ?? '—' }}</strong>
                            @else
                                Sin vehículo
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Fecha programada --}}
                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">event</span>
                    <div>
                        <p class="asi-row-label">Fecha programada</p>
                        <p class="asi-row-val">
                            @if($fpr)
                                {{ \Carbon\Carbon::parse($fpr)->format('d/m/Y') }}
                                @if($sol->hpr_sol)
                                    &nbsp;<small style="color:#aaa;">{{ $sol->hpr_sol }}</small>
                                @endif
                            @else
                                <span style="color:#777;">Sin fecha programada</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Fecha de asignación --}}
                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">assignment_turned_in</span>
                    <div>
                        <p class="asi-row-label">Asignado el</p>
                        <p class="asi-row-val">
                            {{ \Carbon\Carbon::parse($a->fec_asi)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

                {{-- Observación --}}
                @if(!empty($a->obs_asi))
                <div class="asi-obs">
                    <span class="material-symbols-outlined" style="font-size:16px;color:#ff7b00;flex-shrink:0;">notes</span>
                    <p>{{ $a->obs_asi }}</p>
                </div>
                @endif

            </div>

            {{-- Footer: Seguimiento + Finalizar --}}
            <div class="asi-card-footer">

                {{-- Ver seguimiento --}}
                <a href="{{ route('tecnico.seguimiento', $sol->cod_solicitudes) }}"
                class="btn-asi-seguimiento">
                    <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">
                        history
                    </span>
                    Seguimiento
                </a>

                {{-- Finalizar --}}
                @if(!in_array($estado, ['Finalizado', 'Cancelado']))
                    <form method="POST"
                        action="{{ route('tecnico.asignaciones.finalizar', $sol->cod_solicitudes) }}"
                        onsubmit="return confirm('¿Confirmas que este trabajo está finalizado?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-asi-finalizar">
                            <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">
                                task_alt
                            </span>
                            Finalizar
                        </button>
                    </form>
                @else
                    <span class="asi-badge {{ $estadoClass }}">{{ $estadoLabel }}</span>
                @endif

            </div>

        </div>

    @empty
        <div class="asi-empty">
            <span class="material-symbols-outlined" style="font-size:64px;opacity:.25;display:block;margin-bottom:14px;">
                engineering
            </span>
            <p>No tienes solicitudes asignadas por el momento.</p>
        </div>
    @endforelse

</div>

{{-- ══════════════════════════════════════════
     ESTILOS
══════════════════════════════════════════ --}}
<style>
/* ── Subtítulo del header ── */
.asi-subtitulo {
    color: #888;
    font-size: 14px;
    margin: 4px 0 0;
}

/* ── Stats rápidas ── */
.asi-stats {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 28px;
}
.asi-stat-card {
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 10px;
    padding: 14px 22px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    min-width: 110px;
}
.asi-stat-num {
    font-size: 26px;
    font-weight: 700;
    color: #ff7b00;
    line-height: 1;
}
.asi-stat-label {
    font-size: 11px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* ── Grid de tarjetas ── */
.asi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

/* ── Tarjeta ── */
.asi-card {
    background: #2a2a2a;
    border: 1px solid #3a3a3a;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: transform 0.2s, border-color 0.2s;
}
.asi-card:hover {
    transform: translateY(-3px);
    border-color: #ff7b00;
}

/* ── Top de la tarjeta ── */
.asi-card-top {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 18px 14px;
}
.asi-icon-wrap {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tipo-preventivo  { background: rgba(255,123,0,0.12); color: #ff7b00; border: 1px solid rgba(255,123,0,0.3); }
.tipo-correctivo  { background: rgba(79,195,247,0.12); color: #4fc3f7; border: 1px solid rgba(79,195,247,0.3); }
.tipo-preventivo-txt { color: #ff7b00; font-size: 12px; font-weight: 700; display: block; }
.tipo-correctivo-txt { color: #4fc3f7; font-size: 12px; font-weight: 700; display: block; }

.asi-cod {
    font-size: 11px;
    color: #666;
    display: block;
    margin-top: 2px;
}

.asi-badge {
    margin-left: auto;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
    flex-shrink: 0;
}
.badge-pendiente  { background: rgba(108,117,125,0.2); color: #9aa0a6; border: 1px solid rgba(108,117,125,0.4); }
.badge-enproceso  { background: rgba(255,193,7,0.15);  color: #ffc107; border: 1px solid rgba(255,193,7,0.4); }
.badge-finalizado { background: rgba(102,187,106,0.15);color: #66bb6a; border: 1px solid rgba(102,187,106,0.4); }
.badge-cancelado  { background: rgba(229,57,53,0.15);  color: #e57373; border: 1px solid rgba(229,57,53,0.4); }

/* ── Divider ── */
.asi-divider {
    height: 1px;
    background: #3a3a3a;
    margin: 0 18px;
}

/* ── Body ── */
.asi-card-body {
    padding: 14px 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
}
.asi-row {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.asi-row-icon {
    font-size: 18px;
    color: #ff7b00;
    margin-top: 1px;
    flex-shrink: 0;
}
.asi-row-label {
    font-size: 10px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin: 0 0 2px;
}
.asi-row-val {
    font-size: 13px;
    color: #ddd;
    margin: 0;
}

/* ── Observación ── */
.asi-obs {
    display: flex;
    gap: 8px;
    background: rgba(255,123,0,0.06);
    border: 1px solid rgba(255,123,0,0.15);
    border-radius: 8px;
    padding: 8px 12px;
    margin-top: 4px;
}
.asi-obs p {
    font-size: 12px;
    color: #bbb;
    margin: 0;
    line-height: 1.5;
}

/* ── Footer ── */
.asi-card-footer {
    padding: 14px 18px;
    border-top: 1px solid #3a3a3a;
    display: flex;
    justify-content: flex-end;
    gap: 10px;          /* ← espacio entre los dos botones */
    align-items: center;
}

.btn-asi-seguimiento {
    background: #1a2a3a;
    color: #4fc3f7;
    border: 1px solid #1e4a6a;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: all 0.2s;
}
.btn-asi-seguimiento:hover {
    background: #1e5a80;
    color: #fff;
    border-color: #4fc3f7;
}

.btn-asi-finalizar {
    background: #1a3a26;
    color: #66bb6a;
    border: 1px solid #2d6a3a;
    padding: 8px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.btn-asi-finalizar:hover:not(:disabled) {
    background: #28a745;
    color: #fff;
    border-color: #28a745;
}
.btn-asi-finalizar:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

/* ── Empty state ── */
.asi-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 80px 20px;
    color: #555;
    font-size: 16px;
}

/* ── Responsive ── */
@media (max-width: 600px) {
    .asi-grid  { grid-template-columns: 1fr; }
    .asi-stats { gap: 8px; }
    .asi-stat-card { padding: 12px 16px; min-width: 80px; }
}
</style>

@endsection