@extends('layouts.slidebarTecnico')

@section('title', 'Técnico | Finalizados')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">TRABAJOS FINALIZADOS</h1>
    <p class="asi-subtitulo">Historial de solicitudes completadas</p>
</div>

<div class="asi-grid">

    @forelse ($asignaciones as $a)
        @php
            $sol    = $a->solicitud;
            $veh    = $sol->vehiculo ?? null;
            $cli    = $sol->cliente  ?? null;
            $estado = $sol->est_sol  ?? 'Finalizado';

            $estadoClass = $estado === 'Cancelado' ? 'badge-cancelado' : 'badge-finalizado';
            $estadoLabel = $estado;

            $tipoClass = str_contains($sol->tma_sol ?? '', 'Preventivo')
                ? 'tipo-preventivo' : 'tipo-correctivo';
        @endphp

        <div class="asi-card">

            <div class="asi-card-top">
                <div class="asi-icon-wrap {{ $tipoClass }}">
                    <span class="material-symbols-outlined" style="font-size:28px;">
                        {{ str_contains($sol->tma_sol ?? '', 'Preventivo') ? 'shield_check' : 'build' }}
                    </span>
                </div>
                <div>
                    <span class="asi-tipo {{ $tipoClass }}-txt">{{ $sol->tma_sol ?? '—' }}</span>
                    <span class="asi-cod">{{ $sol->cod_solicitudes ?? '—' }}</span>
                </div>
                <span class="asi-badge {{ $estadoClass }}">{{ $estadoLabel }}</span>
            </div>

            <div class="asi-divider"></div>

            <div class="asi-card-body">

                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">account_circle</span>
                    <div>
                        <p class="asi-row-label">Cliente</p>
                        <p class="asi-row-val">
                            {{ $cli ? $cli->nom_cli.' '.$cli->app_cli : 'Sin cliente' }}
                        </p>
                    </div>
                </div>

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

                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">event</span>
                    <div>
                        <p class="asi-row-label">Fecha programada</p>
                        <p class="asi-row-val">
                            {{ $sol->fpr_sol
                                ? \Carbon\Carbon::parse($sol->fpr_sol)->format('d/m/Y')
                                : '—' }}
                        </p>
                    </div>
                </div>

                <div class="asi-row">
                    <span class="material-symbols-outlined asi-row-icon">assignment_turned_in</span>
                    <div>
                        <p class="asi-row-label">Asignado el</p>
                        <p class="asi-row-val">
                            {{ \Carbon\Carbon::parse($a->fec_asi)->format('d/m/Y') }}
                        </p>
                    </div>
                </div>

            </div>

            <div class="asi-card-footer">
                <a href="{{ route('tecnico.seguimiento', $sol->cod_solicitudes) }}"
                   class="btn-asi-seguimiento">
                    <span class="material-symbols-outlined" style="font-size:15px;vertical-align:middle;">
                        history
                    </span>
                    Ver seguimiento
                </a>
            </div>

        </div>

    @empty
        <div class="asi-empty">
            <span class="material-symbols-outlined"
                  style="font-size:64px;opacity:.25;display:block;margin-bottom:14px;">
                check_circle
            </span>
            <p>No tienes trabajos finalizados aún.</p>
        </div>
    @endforelse

</div>

{{-- Reutiliza los mismos estilos de asignaciones --}}
<style>
.asi-subtitulo { color:#888; font-size:14px; margin:4px 0 0; }
.asi-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:20px; }
.asi-card { background:#2a2a2a; border:1px solid #3a3a3a; border-radius:14px; overflow:hidden; display:flex; flex-direction:column; transition:transform 0.2s,border-color 0.2s; }
.asi-card:hover { transform:translateY(-3px); border-color:#ff7b00; }
.asi-card-top { display:flex; align-items:center; gap:12px; padding:16px 18px 14px; }
.asi-icon-wrap { width:50px; height:50px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.tipo-preventivo { background:rgba(255,123,0,0.12); color:#ff7b00; border:1px solid rgba(255,123,0,0.3); }
.tipo-correctivo { background:rgba(79,195,247,0.12); color:#4fc3f7; border:1px solid rgba(79,195,247,0.3); }
.tipo-preventivo-txt { color:#ff7b00; font-size:12px; font-weight:700; display:block; }
.tipo-correctivo-txt { color:#4fc3f7; font-size:12px; font-weight:700; display:block; }
.asi-cod { font-size:11px; color:#666; display:block; margin-top:2px; }
.asi-badge { margin-left:auto; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; white-space:nowrap; flex-shrink:0; }
.badge-finalizado { background:rgba(102,187,106,0.15); color:#66bb6a; border:1px solid rgba(102,187,106,0.4); }
.badge-cancelado { background:rgba(229,57,53,0.15); color:#e57373; border:1px solid rgba(229,57,53,0.4); }
.asi-divider { height:1px; background:#3a3a3a; margin:0 18px; }
.asi-card-body { padding:14px 18px; display:flex; flex-direction:column; gap:10px; flex:1; }
.asi-row { display:flex; align-items:flex-start; gap:10px; }
.asi-row-icon { font-size:18px; color:#ff7b00; margin-top:1px; flex-shrink:0; }
.asi-row-label { font-size:10px; color:#666; text-transform:uppercase; letter-spacing:.4px; margin:0 0 2px; }
.asi-row-val { font-size:13px; color:#ddd; margin:0; }
.asi-card-footer { padding:14px 18px; border-top:1px solid #3a3a3a; display:flex; justify-content:flex-end; gap:10px; align-items:center; }
.btn-asi-seguimiento { background:#1a2a3a; color:#4fc3f7; border:1px solid #1e4a6a; padding:8px 16px; border-radius:8px; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:6px; text-decoration:none; transition:all 0.2s; }
.btn-asi-seguimiento:hover { background:#1e5a80; color:#fff; border-color:#4fc3f7; }
.asi-empty { grid-column:1/-1; text-align:center; padding:80px 20px; color:#555; font-size:16px; }
@media(max-width:600px){ .asi-grid{ grid-template-columns:1fr; } }
</style>

@endsection