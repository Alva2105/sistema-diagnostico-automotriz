@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Solicitudes')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">SOLICITUDES</h1>
</div>

<div class="tabla-container">
    
    <table class="tabla-solicitud">
        <thead>
            <tr>
                <th class="th-solicitud">Nro.</th>
                <th class="th-solicitud">Solicitante</th>
                <th class="th-solicitud">Tipo de mantenimiento</th>
                <th class="th-solicitud">Servicio</th>
                <th class="th-solicitud">Fecha</th>
                <th class="th-solicitud">Estado</th>
                <th class="th-solicitud">Asignaciones</th>
                <th class="th-solicitud">Acciones</th>
            </tr>
        </thead>    

        <tbody>
            @foreach ($solicitudes as $i => $s)
            <tr>
                <td class="td-solicitud">{{ $i + 1 }}</td> 

                <td class="td-solicitud">
                    {{ $s->cliente->nom_cli . ' ' . $s->cliente->app_cli ?? 'Cliente desconocido' }}
                </td>   

                <td class="td-solicitud">
                    {{ $s->tma_sol }}
                </td>   

                <td class="td-solicitud">
                    {{ $s->ser_sol }}
                </td>   

                <td class="td-solicitud">
                    {{ \Carbon\Carbon::parse($s->fec_sol)->format('d/m/Y') }}
                </td>   

                <td class="td-solicitud">
                    <span class="estado {{ strtolower($s->est_sol) }}">
                        {{ $s->est_sol }}
                    </span>
                </td>
                
                <td class="td-solicitud">
                    <button class="detalle-link"
                        data-id="{{ $s->cod_sol }}"
                        data-descripcion="{{ $s->des_sol }}"
                        data-hrpreferida="{{ $s->hpr_sol }}"
                        data-fecpreferida="{{ $s->fpr_sol }}"
                        data-vehiculo="{{ $s->vehiculo->pla_veh ?? '—' }}"
                        data-servicio="{{ $s->ser_sol }}"
                        data-estado="{{ $s->est_sol }}"
                        onclick="mostrarDetalles(this)">
                        Mostrar Detalles
                    </button>
                </td>

                {{-- Acciones --}}
                <td class="td-solicitud">
                    @if($s->est_sol === 'Pendiente')
                        <button class="btn-aprobar" onclick="aprobarSolicitud({{ $s->cod_sol }})">
                            Aprobar
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @include('gerente.detallesSolicitud')
    
    @if ($solicitudes->hasPages())
    <div class="pagination-container">

        {{-- Flecha anterior --}}
        @if ($solicitudes->onFirstPage())
            <span class="page-btn disabled">&#10094;</span>
        @else
            <a href="{{ $solicitudes->previousPageUrl() }}" class="page-btn">&#10094;</a>
        @endif

        {{-- Primera página --}}
        @if ($solicitudes->currentPage() > 3)
            <a href="{{ $solicitudes->url(1) }}" class="page-btn">1</a>
            <span class="page-dots">...</span>
        @endif

        {{-- Números cercanos --}}
        @for ($i = max(1, $solicitudes->currentPage() - 2); 
                $i <= min($solicitudes->lastPage(), $solicitudes->currentPage() + 2); 
                $i++
        )
            @if ($i == $solicitudes->currentPage())
                <span class="page-btn active">{{ $i }}</span>
            @else
                <a href="{{ $solicitudes->url($i) }}" class="page-btn">{{ $i }}</a>
            @endif
        @endfor

        {{-- Última página --}}
        @if ($solicitudes->currentPage() < $solicitudes->lastPage() - 2)
            <span class="page-dots">...</span>
            <a href="{{ $solicitudes->url($solicitudes->lastPage()) }}" class="page-btn">
                {{ $solicitudes->lastPage() }}
            </a>
        @endif

        {{-- Flecha siguiente --}}
        @if ($solicitudes->hasMorePages())
            <a href="{{ $solicitudes->nextPageUrl() }}" class="page-btn">&#10095;</a>
        @else
            <span class="page-btn disabled">&#10095;</span>
        @endif

    </div>
    @endif    

</div>

@push('style')
    @vite('resources/css/listadoSolicitudes.css')
@endpush

@push('scripts')
    @vite('resources/js/solicitud/detalleSolicitud.js')
@endpush

@endsection