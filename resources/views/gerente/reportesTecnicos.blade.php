@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Reportes')
@section('main-class', 'tecnico-scroll')   {{-- importante --}}
@section('content')

<div class="INVheader">
    <h1 class="INVtitle">REPORTES</h1>
</div>

<div class="rt-main">
    <div class="rt-wrapper">

        <!-- CONTROLES: Dos secciones plegables -->
        @php
            // asegurarse que $mantenimientos esté disponible (colección de Eloquent)
            $mantenimientos = $mantenimientos ?? collect();

            // Helper para agrupar (devuelve array -> fecha legible con inicial mayúscula)
            $groupByDateLabel = function($collection) {
                return $collection->groupBy(function($m){
                    $d = \Carbon\Carbon::parse($m->fen_man)->locale('es')->translatedFormat('l - d/m/Y');
                    $first = mb_strtoupper(mb_substr($d, 0, 1, 'UTF-8'), 'UTF-8');
                    $rest  = mb_substr($d, 1, null, 'UTF-8');
                    return $first . $rest;
                });
            };

            // separar por estados (ajusta los valores si tu DB usa otros)
            $proc = $mantenimientos->where('est_man', 'En proceso');
            $fin  = $mantenimientos->where('est_man', 'Finalizado');

            $groupedProc = $groupByDateLabel($proc);
            $groupedFin  = $groupByDateLabel($fin);
        @endphp

        <!-- SECCION: EN PROCESO -->
        <section class="rt-section">
            <header class="rt-section-header">
                <h2 class="rt-section-title">En proceso</h2>
                <div class="rt-section-meta">
                    <span class="count">{{ $proc->count() }}</span>
                    <button class="rt-toggle-btn" data-target="#proc-body" aria-expanded="true" aria-label="Alternar En proceso">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
            </header>

            <div id="proc-body" class="rt-section-body">
                @if($proc->isEmpty())
                    <div class="rt-empty">No hay mantenimientos en proceso.</div>
                @else
                    @foreach($groupedProc as $dateLabel => $mantenimientoForDate)
                        <div class="rt-date-section">
                            <h3 class="rt-date-title">{{ $dateLabel }}</h3>

                            <div class="rt-cards-row">
                                @foreach($mantenimientoForDate as $m)
                                    @php
                                        $placa = $m->vehiculo->pla_veh ?? '—';
                                        $hora = \Carbon\Carbon::parse($m->fen_man)->format('H:i');
                                    @endphp

                                    <div class="rt-card"
                                        role="button"
                                        tabindex="0"
                                        onclick="window.location.href='{{ route('gerente.mantenimiento.detalle', [$tecnico->cod_tau, $m->cod_man]) }}'">
                                        <div class="rt-card-left">
                                            <div class="rt-file-icon"><i class="fa-regular fa-file-lines"></i></div>
                                            <div class="rt-vertical"></div>
                                        </div>

                                        <span class="rt-card-code">{{ $placa }}</span>
                                        <i class="fa-regular fa-clock rt-status rt-spin-slow" aria-hidden="true"></i>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(! $loop->last)
                            <div class="separator-line"></div>
                        @endif
                    @endforeach
                @endif
            </div>
        </section>

        <!-- SECCION: FINALIZADOS -->
        <section class="rt-section">
            <header class="rt-section-header">
                <h2 class="rt-section-title">Finalizado</h2>
                <div class="rt-section-meta">
                    <span class="count">{{ $fin->count() }}</span>
                    <button class="rt-toggle-btn" data-target="#fin-body" aria-expanded="false" aria-label="Alternar Finalizado">
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
            </header>

            <div id="fin-body" class="rt-section-body rt-collapsed">
                @if($fin->isEmpty())
                    <div class="rt-empty">No hay mantenimientos finalizados.</div>
                @else
                    @foreach($groupedFin as $dateLabel => $mantenimientoForDate)
                        <div class="rt-date-section">
                            <h3 class="rt-date-title">{{ $dateLabel }}</h3>

                            <div class="rt-cardsF-row">
                                @foreach($mantenimientoForDate as $m)
                                    @php
                                        $placa = $m->vehiculo->pla_veh ?? '—';
                                        $hora = \Carbon\Carbon::parse($m->fen_man)->format('H:i');
                                    @endphp

                                    <div class="rt-cardF"
                                        role="button"
                                        tabindex="0"
                                        onclick="window.location.href='{{ route('gerente.mantenimiento.detalle', [$tecnico->cod_tau, $m->cod_man]) }}'">
                                        <div class="rt-cardF-left">
                                            <div class="rt-file-icon"><i class="fa-regular fa-file-lines"></i></div>
                                            <div class="rt-vertical"></div>
                                        </div>
                                        <span class="rt-cardF-code">{{ $placa }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if(! $loop->last)
                            <div class="separator-line"></div>
                        @endif
                    @endforeach
                @endif
            </div>
        </section>

    </div>
</div>

{{-- JS toggle (pegar al final del blade o dentro de un script block) --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.rt-toggle-btn').forEach(btn=>{
        btn.addEventListener('click', function(e){
            const target = document.querySelector(this.dataset.target);
            if (!target) return;
            const expanded = this.getAttribute('aria-expanded') === 'true';
            // toggle aria
            this.setAttribute('aria-expanded', !expanded);
            // toggle body
            target.classList.toggle('rt-collapsed', expanded);
        });
    });
});
</script>

@push('style')
    @vite('resources/css/reportesTecnicos.css')
@endpush

@endsection