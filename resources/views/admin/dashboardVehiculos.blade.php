@extends('layouts.dashboard')

@section('contenido')
<div class="Dvehiculos-content">
    <div class="header-content">
        <h2>
            Vehículos

            <button class="btn-nuevoUsu"
                    onclick="abrirModalVehiculo()">
                +Nuevo Vehículo
            </button>
        </h2>
    </div>

    <div class="search-container">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Buscar...">
            <div class="search-icon" id="searchIcon">
                <span class="material-symbols-outlined buscador">search</span>
            </div>
        </div>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Placa</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Color</th>
                    <th>Tipo</th>
                    {{-- CAMBIO: "Cliente" y "Conductor" → solo "Cliente"
                         Conductor fue eliminado de la nueva BD --}}
                    <th>Cliente</th>
                    {{-- CAMBIO: se elimina columna Conductor (no existe en nueva BD) --}}
                    {{-- CAMBIO: se elimina columna Estado (est_veh no existe en nueva BD) --}}
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($vehiculos as $vehiculo)
                    {{-- CAMBIO: cod_veh → cod_vehiculos (PK compuesta, usamos cod_vehiculos) --}}
                    <tr data-id="{{ $vehiculo->cod_vehiculos }}">

                        <td>{{ $vehiculo->cod_vehiculos }}</td>
                        <td>{{ $vehiculo->pla_veh }}</td>
                        <td>{{ $vehiculo->mar_veh }}</td>
                        <td>{{ $vehiculo->mod_veh }}</td>
                        {{-- CAMBIO: anf_veh → ani_veh --}}
                        <td>{{ $vehiculo->ani_veh ?? '-' }}</td>
                        <td>{{ $vehiculo->col_veh ?? '-' }}</td>
                        <td>{{ $vehiculo->tip_veh ?? '-' }}</td>

                        {{-- CAMBIO: antes era {!! $vehiculo->perfil_cliente_html !!} (accessor inexistente)
                             Ahora cargamos la relación cliente directamente --}}
                        <td class="col-cliente">
                            @if ($vehiculo->cliente)
                                <div class="perfil-conductor">
                                    @if ($vehiculo->cliente->img_cli)
                                        <img src="{{ asset('storage/' . $vehiculo->cliente->img_cli) }}"
                                             alt="Cliente" class="img-mini">
                                    @else
                                        <span class="material-symbols-outlined icono-perfil">account_circle</span>
                                    @endif
                                    <span class="nombre-conductor">
                                        {{ $vehiculo->cliente->nom_cli }}
                                        {{ $vehiculo->cliente->app_cli }}
                                    </span>
                                </div>
                            @else
                                <span>Sin cliente</span>
                            @endif
                        </td>

                        {{-- CAMBIO: se elimina col-conductor y estado (no existen en nueva BD) --}}

                        <td class="acciones">

                            <button type="button"
                                    class="btn-editar"
                                    onclick="editarVehiculo(
                                        '{{ $vehiculo->cod_vehiculos }}',
                                        '{{ $vehiculo->cod_clientes_veh }}',
                                        '{{ $vehiculo->pla_veh }}',
                                        '{{ $vehiculo->mar_veh }}',
                                        '{{ $vehiculo->mod_veh }}',
                                        '{{ $vehiculo->ani_veh }}',
                                        '{{ $vehiculo->col_veh }}',
                                        '{{ $vehiculo->tip_veh }}'
                                    )">
                                Editar
                            </button>

                            <form method="POST"
                                action="{{ route('vehiculos.eliminar', [$vehiculo->cod_vehiculos, $vehiculo->cod_clientes_veh]) }}"
                                style="display:inline-block;"
                                onsubmit="return confirm('¿Seguro que deseas eliminar este vehículo?')">

                            @csrf
                            @method('DELETE')

                                <button type="submit" class="btn-eliminar">
                                    Eliminar
                                </button>
                            </form>

                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($vehiculos->hasPages())
    <div class="pagination-container">
        @if ($vehiculos->onFirstPage())
            <span class="page-btn disabled">&#10094;</span>
        @else
            <a href="{{ $vehiculos->previousPageUrl() }}" class="page-btn">&#10094;</a>
        @endif

        @if ($vehiculos->currentPage() > 3)
            <a href="{{ $vehiculos->url(1) }}" class="page-btn">1</a>
            <span class="page-dots">...</span>
        @endif

        @for ($i = max(1, $vehiculos->currentPage() - 2);
              $i <= min($vehiculos->lastPage(), $vehiculos->currentPage() + 2);
              $i++)
            @if ($i == $vehiculos->currentPage())
                <span class="page-btn active">{{ $i }}</span>
            @else
                <a href="{{ $vehiculos->url($i) }}" class="page-btn">{{ $i }}</a>
            @endif
        @endfor

        @if ($vehiculos->currentPage() < $vehiculos->lastPage() - 2)
            <span class="page-dots">...</span>
            <a href="{{ $vehiculos->url($vehiculos->lastPage()) }}" class="page-btn">
                {{ $vehiculos->lastPage() }}
            </a>
        @endif

        @if ($vehiculos->hasMorePages())
            <a href="{{ $vehiculos->nextPageUrl() }}" class="page-btn">&#10095;</a>
        @else
            <span class="page-btn disabled">&#10095;</span>
        @endif
    </div>
    @endif
</div>

{{-- MODAL VEHICULO --}}
<div id="modalVehiculo" class="modal-usuario">

    <div class="modal-contenido">

        <div class="modal-header">
            <h2 id="tituloModalVehiculo">Registrar Vehículo</h2>

            <span class="cerrar-modal"
                  onclick="cerrarModalVehiculo()">
                &times;
            </span>
        </div>

        <form id="formVehiculo"
              method="POST">

            @csrf

            <input type="hidden"
                   name="_method"
                   id="methodVehiculo"
                   value="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Placa</label>
                    <input type="text" name="pla_veh" required>
                </div>

                <div class="form-group">
                    <label>Marca</label>
                    <input type="text" name="mar_veh" required>
                </div>

                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="mod_veh" required>
                </div>

                <div class="form-group">
                    <label>Año</label>
                    <input type="number" name="ani_veh">
                </div>

                <div class="form-group">
                    <label>Color</label>
                    <input type="text" name="col_veh">
                </div>

                <div class="form-group">
                    <label>Tipo</label>
                    <input type="text" name="tip_veh">
                </div>

                <div class="form-group">
                    <label>Cliente</label>

                    <select name="cod_clientes_veh" required>

                        <option value="">Seleccione...</option>

                        @foreach(\App\Models\Cliente::all() as $cliente)

                            <option value="{{ $cliente->cod_clientes }}">
                                {{ $cliente->nom_cli }}
                                {{ $cliente->app_cli }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

            <div class="modal-botones">

                <button type="submit"
                        class="btn-guardar-modal">
                    Guardar
                </button>

                <button type="button"
                        class="btn-cancelar-modal"
                        onclick="cerrarModalVehiculo()">
                    Cancelar
                </button>

            </div>

        </form>

    </div>

</div>

<style>
    .perfil-conductor {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .img-mini {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ff7b00;
    }
    .icono-perfil {
        font-size: 38px;
        color: #ff7b00;
    }
    .nombre-conductor {
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }
    .buscador { color: #000; }

    /* Overlay del modal */
    .modal-usuario {
        display: none;           /* oculto por defecto */
        position: fixed;
        inset: 0;                /* top/right/bottom/left: 0 */
        background: rgba(0, 0, 0, 0.6);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    /* Caja del modal */
    .modal-contenido {
        background: #2a2a2a;
        border: 2px solid #ff7b00;
        border-radius: 10px;
        padding: 30px;
        width: 600px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .modal-header h2 {
        color: #ff7b00;
        margin: 0;
    }

    .cerrar-modal {
        font-size: 24px;
        cursor: pointer;
        color: #ccc;
    }

    .cerrar-modal:hover { color: #fff; }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group label {
        display: block;
        color: #ccc;
        margin-bottom: 6px;
        font-size: 13px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px 12px;
        background: #3a3a3a;
        border: 1px solid #555;
        border-radius: 6px;
        color: #fff;
        font-size: 14px;
    }

    .modal-botones {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
    }

    .btn-guardar-modal {
        background: #ff7b00;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
    }

    .btn-cancelar-modal {
        background: #555;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 6px;
        cursor: pointer;
    }
</style>

<script>
    function abrirModalVehiculo() {
    const modal    = document.getElementById('modalVehiculo');
    const titulo   = document.getElementById('tituloModalVehiculo');
    const form     = document.getElementById('formVehiculo');
    const method   = document.getElementById('methodVehiculo');

    // Título y método HTTP para creación
    titulo.textContent = 'Registrar Vehículo';
    method.value       = 'POST';

    // Action apunta a la ruta guardar
    form.action = '/dashboard/vehiculos/guardar';

    // Limpiar campos
    form.reset();

    // Mostrar modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden'; // evita scroll del fondo
}

// ─── Abrir modal para EDITAR vehículo ──────────────────────────────────────
    function editarVehiculo(codVehiculo, codCliente, placa, marca, modelo, anio, color, tipo) {
    const modal    = document.getElementById('modalVehiculo');
    const titulo   = document.getElementById('tituloModalVehiculo');
    const form     = document.getElementById('formVehiculo');
    const method   = document.getElementById('methodVehiculo');

    // Título y método HTTP para edición
    titulo.textContent = 'Editar Vehículo';
    method.value       = 'PUT';

    // Action apunta a la ruta actualizar con PK compuesta
    form.action = `/dashboard/vehiculos/${codVehiculo}/${codCliente}/actualizar`;

    // Rellenar campos con los datos actuales
    form.querySelector('[name="pla_veh"]').value = placa  ?? '';
    form.querySelector('[name="mar_veh"]').value = marca  ?? '';
    form.querySelector('[name="mod_veh"]').value = modelo ?? '';
    form.querySelector('[name="ani_veh"]').value = anio   ?? '';
    form.querySelector('[name="col_veh"]').value = color  ?? '';
    form.querySelector('[name="tip_veh"]').value = tipo   ?? '';

    // Seleccionar el cliente correcto en el <select>
    const selectCliente = form.querySelector('[name="cod_clientes_veh"]');
    if (selectCliente) {
        selectCliente.value = codCliente;
    }

    // Mostrar modal
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

// ─── Cerrar modal ───────────────────────────────────────────────────────────
    function cerrarModalVehiculo() {
    const modal = document.getElementById('modalVehiculo');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

// ─── Cerrar al hacer clic fuera del contenido ───────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalVehiculo');

    if (modal) {
        modal.addEventListener('click', function (e) {
            // Si el clic fue directo sobre el fondo (no sobre .modal-contenido)
            if (e.target === modal) {
                cerrarModalVehiculo();
            }
        });
    }
});
</script>

@push('scripts')
    @vite('resources/js/dashboard/buscadorVehiculos.js')
@endpush

@endsection