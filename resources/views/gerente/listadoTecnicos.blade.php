@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Listado de Técnicos')

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">LISTA DE TÉCNICOS</h1>
</div>

<div class="tabla-container">

    {{-- Barra superior: búsqueda rápida (opcional) --}}
    <div class="searchT-container">
        <div class="searchT-box">
            <input type="text" id="searchTInput" placeholder="Buscar...">
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
                <th class="th-solicitud">Especialidad</th>
                <th class="th-solicitud">Correo</th>
                <th class="th-solicitud">F. Nacimiento</th>
                <th class="th-solicitud">Estado</th>
                <th class="th-solicitud">Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tecnicos as $index => $t)
            @php
                // Relaciones seguras
                $usuario = $t->usuario ?? null;
                $registro = $usuario->registro ?? null;
                $foto = $usuario->img_usu ?? null;
            @endphp
            <tr>
                <td class="td-solicitud">
                    {{ $t->cod_tau }}
                </td>

                <td class="td-solicitud">
                    {{ trim(($registro->nom_reg ?? '') . ' ' . ($registro->apa_reg ?? '') . ' ' . ($registro->ama_reg ?? '')) ?: '—' }}
                </td>

                <td class="td-solicitud">
                    {{ $registro->cie_reg ?? '—' }}
                </td>

                <td class="td-solicitud">
                    {{ $t->esp_tau ?? '—' }}
                </td>

                <td class="td-solicitud">
                    {{ $registro->coe_reg ?? '—' }}
                </td>

                <td class="td-solicitud">
                    @if(!empty($registro->fna_reg))
                        {{ \Carbon\Carbon::parse($registro->fna_reg)->format('d/m/Y') }}
                    @else
                        —
                    @endif
                </td>

                <td class="td-solicitud">
                    <span class="estado {{ strtolower($usuario->est_usu ?? 'desconocido') }}">
                        {{ $usuario->est_usu ?? 'Desconocido' }}
                    </span>
                </td>

                <td class="td-solicitud">
                    <a href="{{ route('gerente.tecnico.reportes', $t->cod_usuarios) }}" class="btn-aprobar">
                        Ver Reportes
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td class="td-solicitud" colspan="11">No se encontraron técnicos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Paginación (si $tecnicos es LengthAwarePaginator) --}}
    <div class="pagination-wrapper">
        @if(method_exists($tecnicos, 'links'))
            {{ $tecnicos->withQueryString()->links() }}
        @endif
    </div>

</div>

<style>
    /*! ====== SEARCH ====== */
.searchT-container {
    flex: 1;
    max-width: 400px;
    position: relative;
}

.searchT-box {
    position: relative;
    width: 100%;
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
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.searchT-box input::placeholder {
    color: rgb(0, 0, 0);
    opacity: 1;
}

.searchT-box input:focus {
    background-color: #ffffff;
    transform: scale(1.05);
    color: black;
}

.searchT-icon {
    position: absolute;
    right: 15px;
    top: 70%;
    transform: translateY(-50%);
    width: 29px;
    height: auto;
    background-size: cover;
    transition: transform 0.3s ease;
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
}

.searchT-box input:focus+.search-icon {
    transform: translateY(-50%) scale(1.2);
}

.search{
    color: black;
}
</style>

@push('scripts')
    @vite('resources/js/reportes/buscadorTecnicos.js')
@endpush

@push('style')
    @vite('resources/css/listadoSolicitudes.css')
@endpush

@endsection