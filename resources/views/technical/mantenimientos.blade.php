@extends('layouts.slidebarTecnico')

@section('title', 'Técnico | Mantenimientos')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">MANTENIMIENTOS</h1>
</div>
<!-- TABLERO -->
<div class="seguimientos-cards">

    @forelse ($mantenimientos as $m)
        <div class="mant-card">
            <div class="mant-card-icon">
                <i class="fas fa-car"></i>
                <i class="fas fa-cog"></i>
            </div>

            <p>{{ $m->vehiculo->pla_veh ?? 'Sin vehículo' }}</p>

            <a href="{{ route('tecnico.seguimiento', $m->cod_man) }}?from=asignados" class="btn-abrir">
                Abrir
            </a>

        </div>

    @empty
        <p style="color:white; font-size:18px;">No tienes mantenimientos asignados.</p>
    @endforelse

</div>

@endsection