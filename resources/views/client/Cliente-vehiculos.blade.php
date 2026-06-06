@extends('layouts.slidebarCliente')

@section('title', 'Cliente | Mis Vehículos')

@section('content')

<div class="vehicles-grid">

    @foreach($vehiculos as $vehiculo)
    <div class="card-container">
        <div class="card">

            {{-- ===================== FRENTE ===================== --}}
            <div class="card-face">

                @php
                    $colorNombre = strtolower(trim($vehiculo->col_veh ?? ''));
                    $colorCss = match ($colorNombre) {
                        'rojo'      => '#e53935',
                        'azul'      => '#1e88e5',
                        'verde'     => '#43a047',
                        'negro'     => '#212121',
                        'blanco'    => '#fafafa',
                        'gris', 'plateado', 'plata' => '#b0bec5',
                        'amarillo'  => '#fdd835',
                        'naranja'   => '#fb8c00',
                        default     => '#ffa000',
                    };
                @endphp

                <div class="avatar-icon pulse" style="border-color: {{ $colorCss }};">
                    <span class="material-symbols-outlined" style="color: {{ $colorCss }};">
                        directions_car
                    </span>
                </div>

                <div class="vehiculo-info">

                    <p><strong>Placa:</strong> {{ $vehiculo->cod_vehiculos ?? '—' }}</p>
                    <p><strong>Marca del Vehículo:</strong> {{ $vehiculo->mar_veh ?? '—' }}</p>
                    <p><strong>Tipo:</strong> {{ $vehiculo->tip_veh ?? '—' }}</p>
                    <p><strong>Modelo del Vehículo:</strong> {{ $vehiculo->mod_veh ?? '—' }}</p>
                    <p><strong>Año:</strong> {{ $vehiculo->ani_veh ?? '—' }}</p>
                    <p><strong>Color:</strong> {{ $vehiculo->col_veh ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach


    {{-- SI NO TIENE VEHÍCULOS --}}
    @if($vehiculos->isEmpty())
        <p style="color:white; text-align:center; width:100%; margin-top:40px;">
            No tienes vehículos registrados.
        </p>
    @endif

</div>

@endsection
