@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Kardex de Clientes')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">KARDEX DE CLIENTES</h1>
</div>


<div class="tabla-container">

    {{-- Barra superior: búsqueda --}}
    <div class="searchT-container">
        <div class="searchT-box">
            <input type="text" id="searchTInput" placeholder="Buscar cliente...">
            <div class="searchT-icon" id="searchTIcon">
                <span class="search material-symbols-outlined buscador">search</span>
            </div>
        </div>
    </div>

    <table class="tabla-solicitud">
        <thead>
            <tr>
                <th class="th-solicitud">ID</th>
                <th class="th-solicitud">Nombre completo</th>
                <th class="th-solicitud">Teléfono</th>
                <th class="th-solicitud">Correo</th>
                <th class="th-solicitud">F. Nacimiento</th>
                <th class="th-solicitud">Estado</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($clientes as $c)

                @php
                    $usuario = $c->usuario ?? null;
                    $registro = $usuario->registro ?? null;
                @endphp

                <tr>
                    <td class="td-solicitud">
                        {{ $c->cod_cli }}
                    </td>

                    <td class="td-solicitud">
                        {{ trim(($registro->nom_reg ?? '') . ' ' . ($registro->apa_reg ?? '') . ' ' . ($registro->ama_reg ?? '')) ?: '—' }}
                    </td>

                    {{-- Teléfono (cliente.tel_cli) --}}
                    <td class="td-solicitud">
                        {{ $c->tel_cli ?? '—' }}
                    </td>

                    {{-- Correo (registro.coe_reg) --}}
                    <td class="td-solicitud">
                        {{ $registro->coe_reg ?? '—' }}
                    </td>

                    {{-- Fecha nacimiento --}}
                    <td class="td-solicitud">
                        @if(!empty($registro->fna_reg))
                            {{ \Carbon\Carbon::parse($registro->fna_reg)->format('d/m/Y') }}
                        @else
                            —
                        @endif
                    </td>

                    {{-- Estado usuario --}}
                    <td class="td-solicitud">
                        <span class="estado {{ strtolower($usuario->est_usu ?? 'desconocido') }}">
                            {{ $usuario->est_usu ?? 'Desconocido' }}
                        </span>
                    </td>
                </tr>

            @empty
                <tr>
                    <td class="td-solicitud" colspan="11">No se encontraron clientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if ($clientes->hasPages())
    <div class="pagination-container">

        {{-- Flecha anterior --}}
        @if ($clientes->onFirstPage())
            <span class="page-btn disabled">&#10094;</span>
        @else
            <a href="{{ $clientes->previousPageUrl() }}" class="page-btn">&#10094;</a>
        @endif

        {{-- Primera página --}}
        @if ($clientes->currentPage() > 3)
            <a href="{{ $clientes->url(1) }}" class="page-btn">1</a>
            <span class="page-dots">...</span>
        @endif

        {{-- Números cercanos --}}
        @for ($i = max(1, $clientes->currentPage() - 2); 
                $i <= min($clientes->lastPage(), $clientes->currentPage() + 2); 
                $i++
        )
            @if ($i == $clientes->currentPage())
                <span class="page-btn active">{{ $i }}</span>
            @else
                <a href="{{ $clientes->url($i) }}" class="page-btn">{{ $i }}</a>
            @endif
        @endfor

        {{-- Última página --}}
        @if ($clientes->currentPage() < $clientes->lastPage() - 2)
            <span class="page-dots">...</span>
            <a href="{{ $clientes->url($clientes->lastPage()) }}" class="page-btn">
                {{ $clientes->lastPage() }}
            </a>
        @endif

        {{-- Flecha siguiente --}}
        @if ($clientes->hasMorePages())
            <a href="{{ $clientes->nextPageUrl() }}" class="page-btn">&#10095;</a>
        @else
            <span class="page-btn disabled">&#10095;</span>
        @endif

    </div>
    @endif    

</div>

{{-- CSS buscador --}}
<style>
.searchT-container {
    flex: 1;
    max-width: 400px;
    position: relative;
}
.searchT-box {
    width: 100%;
    position: relative;
}
.searchT-box input {
    width: 100%;
    padding: 17px 45px 17px 12px;
    border-radius: 40px;
    margin-top: 10%;
    border-color: #ffffff;
    outline: none;
    font-size: 19px;
    background-color: #ffffff;
    color: #000;
    transition: 0.3s ease;
}
.searchT-icon {
    position: absolute;
    right: 15px;
    top: 70%;
    transform: translateY(-50%);
    cursor: pointer;
}
.search { color: black; }
</style>

@push('scripts')
    @vite('resources/js/reportes/buscadorClientes.js')
@endpush

@push('style')
    @vite('resources/css/listadoSolicitudes.css')
@endpush

@endsection
