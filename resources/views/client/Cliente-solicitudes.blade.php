@extends('layouts.slidebarCliente')

@section('title', 'Cliente | Mis Solicitudes')

@section('content')

<div class="solicitudes-grid">

    @foreach($solicitudes as $solicitud)
    <div class="card-container">

        <div class="flip-card">
            <div class="flip-inner">

                {{-- ===================== FRENTE ===================== --}}
                <div class="card-faces front">

                    @php
                        $estado = strtolower(trim($solicitud->est_sol ?? ''));
                        $colorCss = match ($estado) {
                            'pendiente'  => '#cf4500ff',
                            'en_proceso' => '#1e88e5',
                            'finalizado' => '#43a047',
                            'cancelado'  => '#e53935',
                            default      => '#ae6e00ff',
                        };
                    @endphp

                    <div class="avatar-icon pulse" style="border-color: {{ $colorCss }};">
                        <span class="material-symbols-outlined" style="color: {{ $colorCss }};">
                            assignment
                        </span>
                    </div>

                    <div class="solicitud-info">
                        <p><strong>Tipo:</strong> {{ $solicitud->tma_sol ?? '—' }}</p>
                        <p><strong>Servicio:</strong> {{ $solicitud->ser_sol ?? '—' }}</p>
                        <p><strong>Vehículo:</strong> {{ $solicitud->vehiculo->cod_vehiculos ?? 'Sin asignar' }}</p>
                        <p><strong>Fecha de creación:</strong> {{ \Carbon\Carbon::parse($solicitud->fec_sol)->format('d/m/Y') }}</p>
                        <p><strong>Estado:</strong>
                            <span style="color: {{ $colorCss }}; font-weight:bold;">
                                {{ $solicitud->est_sol }}
                            </span>
                        </p>
                    </div>

                    <button class="btn-vermas">Ver más</button>
                </div>

                {{-- ===================== PARTE TRASERA ===================== --}}
                <div class="card-faces back">

                    <h3 class="titulo-back">DETALLES DE LA SOLICITUD</h3>

                    <div class="solicitud-info">
                        <p><strong>Descripción:</strong> {{ $solicitud->obs_sol ?? '—' }}</p>
                        <p><strong>Fecha preferida:</strong> {{ $solicitud->fpr_sol ? \Carbon\Carbon::parse($solicitud->fpr_sol)->format('d/m/Y') : '—' }}</p>
                        <p><strong>Hora preferida:</strong> {{ $solicitud->hpr_sol ?? '—' }}</p>
                    </div>

                    <button class="btn-volver">Volver</button>

                </div>

            </div>
        </div>
    </div>
    @endforeach


    {{-- SI NO TIENE SOLICITUDES --}}
    @if($solicitudes->isEmpty())
        <p style="color:white; text-align:center; width:100%; margin-top:40px;">
            No tienes solicitudes registradas.
        </p>
    @endif

</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    // BOTÓN VER MÁS → gira la card
    document.querySelectorAll(".btn-vermas").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest(".flip-card");
            if (card) card.classList.add("flipped");
        });
    });

    // BOTÓN VOLVER → regresa al frente
    document.querySelectorAll(".btn-volver").forEach(btn => {
        btn.addEventListener("click", e => {
            e.preventDefault();
            e.stopPropagation();

            const card = btn.closest(".flip-card");
            if (card) card.classList.remove("flipped");
        });
    });

});
</script>


@endsection
