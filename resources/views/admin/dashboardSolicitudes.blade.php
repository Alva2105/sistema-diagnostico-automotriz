@extends('layouts.dashboard')

@section('contenido')

<div class="Dsol-content">

    {{-- ══════════════════════════════════════════
         HEADER
    ══════════════════════════════════════════ --}}
    <div class="header-content">
        <h2>
            Solicitudes
            <button class="btn-nuevoUsu" onclick="abrirModalSolicitud()">+ Nueva Solicitud</button>
            <a href="{{ route('solicitudes.reporte') }}" class="btn-reporte">
                <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">
                    summarize
                </span>
                Reporte
            </a>
        </h2>
    </div>

    {{-- ══════════════════════════════════════════
         PESTAÑAS: Activas / Papelera
    ══════════════════════════════════════════ --}}
    <div class="tabs-seccion">
        <button class="tab-btn active" onclick="cambiarTab('activas', this)">
            <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">assignment</span>
            Solicitudes activas
            <span class="tab-count">{{ $solicitudes->total() }}</span>
        </button>
        <button class="tab-btn" onclick="cambiarTab('papelera', this)">
            <span class="material-symbols-outlined" style="font-size:16px;vertical-align:middle;">delete</span>
            Papelera
            <span class="tab-count {{ $eliminadas->count() > 0 ? 'has-items' : '' }}">
                {{ $eliminadas->count() }}
            </span>
        </button>
    </div>

    {{-- ══════════════════════════════════════════
         SECCIÓN: SOLICITUDES ACTIVAS
    ══════════════════════════════════════════ --}}
    <div id="seccion-activas">

        <div class="filtros-estado">
            <button class="filtro-btn active" data-estado="TODOS">Todos</button>
            <button class="filtro-btn" data-estado="Pendiente">Pendiente</button>
            <button class="filtro-btn" data-estado="En_Proceso">En proceso</button>
            <button class="filtro-btn" data-estado="Finalizado">Finalizado</button>
            <button class="filtro-btn" data-estado="Cancelado">Cancelado</button>
        </div>

        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput"
                       placeholder="Buscar por cliente, vehículo, tipo, estado...">
                <div class="search-icon" id="searchIcon">
                    <span class="material-symbols-outlined buscador">search</span>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table id="tablaSolicitudes">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <th>Tipo</th>
                        <th>Servicio</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th>F. Programada</th>
                        <th>Observación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $s)
                        <tr data-id="{{ $s->cod_solicitudes }}"
                            data-estado="{{ $s->est_sol }}">

                            <td class="id-usuario">
                                <span class="material-symbols-outlined icono-perfil">assignment</span>
                                {{ $s->cod_solicitudes }}
                            </td>

                            <td>
                                @if ($s->cliente)
                                    <div class="perfil-conductor">
                                        <span class="material-symbols-outlined icono-perfil">account_circle</span>
                                        <span>{{ $s->cliente->nom_cli }} {{ $s->cliente->app_cli }}</span>
                                    </div>
                                @else
                                    <span>Sin cliente</span>
                                @endif
                            </td>

                            <td>
                                @if ($s->vehiculo)
                                    <div class="perfil-conductor">
                                        <span class="material-symbols-outlined icono-perfil">directions_car</span>
                                        <span>
                                            {{ $s->vehiculo->mar_veh }} {{ $s->vehiculo->mod_veh }}
                                            @if($s->vehiculo->ani_veh)({{ $s->vehiculo->ani_veh }})@endif
                                        </span>
                                    </div>
                                @else
                                    <span>Sin vehículo</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge-tipo {{ strtolower(str_replace(' ', '-', $s->tma_sol)) }}">
                                    {{ $s->tma_sol }}
                                </span>
                            </td>

                            <td>{{ $s->ser_sol ?? '—' }}</td>

                            <td>
                                <span class="estado {{ strtolower(str_replace([' ','_'], '-', $s->est_sol ?? 'pendiente')) }}">
                                    {{ $s->est_sol ?? 'Pendiente' }}
                                </span>
                                <select name="est_sol" class="estado-select select-estado" style="display:none;">
                                    <option value="Pendiente"  {{ ($s->est_sol ?? '') === 'Pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                    <option value="En_Proceso" {{ ($s->est_sol ?? '') === 'En_Proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="Finalizado" {{ ($s->est_sol ?? '') === 'Finalizado' ? 'selected' : '' }}>Finalizado</option>
                                    <option value="Cancelado"  {{ ($s->est_sol ?? '') === 'Cancelado'  ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </td>

                            <td>
                                {{ $s->fec_sol ? \Carbon\Carbon::parse($s->fec_sol)->format('d/m/Y') : '—' }}
                            </td>

                            <td>
                                @if($s->fpr_sol)
                                    {{ \Carbon\Carbon::parse($s->fpr_sol)->format('d/m/Y') }}
                                    @if($s->hpr_sol)
                                        <br><small style="color:#aaa;">{{ $s->hpr_sol }}</small>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>

                            <td class="obs-col">{{ $s->obs_sol ?? '—' }}</td>

                            <td class="acciones">
                                <button type="button" class="btn-asignar"
                                        onclick="abrirModalAsignar(
                                            '{{ $s->cod_solicitudes }}',
                                            `{{ addslashes($s->obs_sol ?? '') }}`
                                        )">
                                    <span class="material-symbols-outlined" style="font-size:14px;vertical-align:middle;">engineering</span>
                                    Asignar
                                </button>

                                <button type="button" class="btn-editar"
                                        onclick="editarSolicitud(
                                            '{{ $s->cod_solicitudes }}',
                                            '{{ $s->cod_clientes_sol }}',
                                            '{{ $s->cod_vehiculos_sol }}',
                                            '{{ $s->tma_sol }}',
                                            '{{ $s->est_sol }}',
                                            `{{ addslashes($s->obs_sol ?? '') }}`,
                                            `{{ addslashes($s->ser_sol ?? '') }}`,
                                            '{{ $s->fec_sol }}',
                                            '{{ $s->fpr_sol }}',
                                            '{{ $s->hpr_sol }}'
                                        )">
                                    Editar
                                </button>

                                <button type="button" class="btn-guardar" style="display:none;"
                                        onclick="guardarEstadoSolicitud(this)">
                                    Guardar
                                </button>

                                {{-- Borrado lógico: abre modal de confirmación --}}
                                <button type="button" class="btn-eliminar"
                                        onclick="confirmarEliminar(
                                            '{{ $s->cod_solicitudes }}',
                                            '{{ addslashes(($s->cliente->nom_cli ?? '') . ' ' . ($s->cliente->app_cli ?? '')) }}'
                                        )">
                                    Eliminar
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align:center; color:#aaa; padding:30px;">
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if ($solicitudes->hasPages())
        <div class="pagination-container">
            @if ($solicitudes->onFirstPage())
                <span class="page-btn disabled">&#10094;</span>
            @else
                <a href="{{ $solicitudes->previousPageUrl() }}" class="page-btn">&#10094;</a>
            @endif

            @if ($solicitudes->currentPage() > 3)
                <a href="{{ $solicitudes->url(1) }}" class="page-btn">1</a>
                <span class="page-dots">...</span>
            @endif

            @for ($i = max(1, $solicitudes->currentPage() - 2);
                  $i <= min($solicitudes->lastPage(), $solicitudes->currentPage() + 2); $i++)
                @if ($i == $solicitudes->currentPage())
                    <span class="page-btn active">{{ $i }}</span>
                @else
                    <a href="{{ $solicitudes->url($i) }}" class="page-btn">{{ $i }}</a>
                @endif
            @endfor

            @if ($solicitudes->currentPage() < $solicitudes->lastPage() - 2)
                <span class="page-dots">...</span>
                <a href="{{ $solicitudes->url($solicitudes->lastPage()) }}" class="page-btn">
                    {{ $solicitudes->lastPage() }}
                </a>
            @endif

            @if ($solicitudes->hasMorePages())
                <a href="{{ $solicitudes->nextPageUrl() }}" class="page-btn">&#10095;</a>
            @else
                <span class="page-btn disabled">&#10095;</span>
            @endif
        </div>
        @endif

    </div>{{-- /seccion-activas --}}

    {{-- ══════════════════════════════════════════
         SECCIÓN: PAPELERA
    ══════════════════════════════════════════ --}}
    <div id="seccion-papelera" style="display:none;">

        <div class="papelera-header">
            <span class="material-symbols-outlined" style="color:#dc3545;font-size:22px;vertical-align:middle;">
                delete_sweep
            </span>
            <span>Solicitudes eliminadas — pueden restaurarse en cualquier momento.</span>
        </div>

        @if($eliminadas->isEmpty())
            <div style="text-align:center; color:#aaa; padding:50px 0;">
                <span class="material-symbols-outlined"
                      style="font-size:52px; display:block; margin-bottom:10px; opacity:.3;">
                    delete_outline
                </span>
                La papelera está vacía.
            </div>
        @else
            <div class="table-wrapper">
                <table id="tablaPapelera">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Vehículo</th>
                            <th>Tipo</th>
                            <th>Servicio</th>
                            <th>Estado</th>
                            <th>Fecha solicitud</th>
                            <th>Eliminado el</th>
                            <th>Restaurado el</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($eliminadas as $e)
                            <tr data-id="{{ $e->cod_solicitudes }}">

                                <td class="id-usuario">
                                    <span class="material-symbols-outlined icono-perfil"
                                          style="color:#666;">assignment_late</span>
                                    {{ $e->cod_solicitudes }}
                                </td>

                                <td>
                                    @if($e->cliente)
                                        <div class="perfil-conductor">
                                            <span class="material-symbols-outlined icono-perfil">account_circle</span>
                                            <span>{{ $e->cliente->nom_cli }} {{ $e->cliente->app_cli }}</span>
                                        </div>
                                    @else
                                        <span style="color:#777;">Sin cliente</span>
                                    @endif
                                </td>

                                <td>
                                    @if($e->vehiculo)
                                        <div class="perfil-conductor">
                                            <span class="material-symbols-outlined icono-perfil">directions_car</span>
                                            <span>{{ $e->vehiculo->mar_veh }} {{ $e->vehiculo->mod_veh }}</span>
                                        </div>
                                    @else
                                        <span style="color:#777;">Sin vehículo</span>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge-tipo {{ strtolower(str_replace(' ', '-', $e->tma_sol)) }}">
                                        {{ $e->tma_sol }}
                                    </span>
                                </td>

                                <td>{{ $e->ser_sol ?? '—' }}</td>

                                <td>
                                    <span class="estado {{ strtolower(str_replace([' ','_'], '-', $e->est_sol ?? 'pendiente')) }}">
                                        {{ $e->est_sol ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $e->fec_sol ? \Carbon\Carbon::parse($e->fec_sol)->format('d/m/Y') : '—' }}
                                </td>

                                {{-- Fecha de eliminación lógica --}}
                                <td>
                                    <span style="color:#e57373; font-size:12px;">
                                        {{ \Carbon\Carbon::parse($e->deleted_at)->format('d/m/Y H:i') }}
                                    </span>
                                </td>

                                {{-- Fecha de última restauración --}}
                                <td>
                                    @if($e->restored_at)
                                        <span style="color:#4caf50; font-size:12px;">
                                            {{ \Carbon\Carbon::parse($e->restored_at)->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span style="color:#555; font-size:12px;">—</span>
                                    @endif
                                </td>

                                <td class="acciones">
                                    <button type="button" class="btn-restaurar"
                                            onclick="confirmarRestaurar(
                                                '{{ $e->cod_solicitudes }}',
                                                '{{ addslashes(($e->cliente->nom_cli ?? '') . ' ' . ($e->cliente->app_cli ?? '')) }}'
                                            )">
                                        <span class="material-symbols-outlined"
                                              style="font-size:14px; vertical-align:middle;">restore</span>
                                        Restaurar
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>{{-- /seccion-papelera --}}

</div>{{-- /Dsol-content --}}

{{-- ══════════════════════════════════════════════════
     MODAL — CONFIRMAR ELIMINACIÓN LÓGICA
══════════════════════════════════════════════════ --}}
<div id="modalConfirmarEliminar"
     style="display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.65); z-index:2000;
            justify-content:center; align-items:center;">
    <div class="modal-confirm-box">
        <div class="modal-confirm-icon">
            <span class="material-symbols-outlined">delete_forever</span>
        </div>
        <h3 class="modal-confirm-titulo">¿Eliminar solicitud?</h3>
        <p class="modal-confirm-texto">
            La solicitud de <strong id="confirmNombreCliente">—</strong>
            se moverá a la papelera.<br>
            <span style="color:#aaa; font-size:13px;">
                Podrás restaurarla desde la pestaña "Papelera".
            </span>
        </p>
        <div class="modal-confirm-botones">
            <button class="btn-confirm-cancelar" onclick="cerrarModalConfirmar()">Cancelar</button>
            <button class="btn-confirm-eliminar" onclick="ejecutarEliminar()">Sí, eliminar</button>
        </div>
        <form id="formEliminar" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL — CONFIRMAR RESTAURACIÓN
══════════════════════════════════════════════════ --}}
<div id="modalConfirmarRestaurar"
     style="display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.65); z-index:2000;
            justify-content:center; align-items:center;">
    <div class="modal-confirm-box">
        <div class="modal-confirm-icon restaurar">
            <span class="material-symbols-outlined">restore</span>
        </div>
        <h3 class="modal-confirm-titulo">¿Restaurar solicitud?</h3>
        <p class="modal-confirm-texto">
            La solicitud de <strong id="confirmNombreRestaurar">—</strong>
            volverá a estar activa con su estado anterior.
        </p>
        <div class="modal-confirm-botones">
            <button class="btn-confirm-cancelar" onclick="cerrarModalRestaurar()">Cancelar</button>
            <button class="btn-confirm-restaurar" onclick="ejecutarRestaurar()">Sí, restaurar</button>
        </div>
        <form id="formRestaurar" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════
     MODAL — CREAR / EDITAR SOLICITUD
══════════════════════════════════════════════════ --}}
<div id="modalSolicitud"
     style="display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.6); z-index:1000;
            justify-content:center; align-items:center;">
    <div class="modal-contenido-sol">
        <div class="modal-header">
            <h2 id="tituloModalSol">Registrar Solicitud</h2>
            <span class="cerrar-modal" onclick="cerrarModalSolicitud()">&times;</span>
        </div>

        <form id="formSolicitud"
              action="{{ route('solicitudes.guardar') }}"
              method="POST">
            @csrf
            <input type="hidden" id="methodSol" name="_method" value="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>Cliente</label>
                    <select name="cod_clientes_sol" id="selectCliente" required
                            onchange="cargarVehiculos(this.value)">
                        <option value="">Seleccione un cliente...</option>
                        @foreach(\App\Models\Cliente::orderBy('nom_cli')->get() as $cli)
                            <option value="{{ $cli->cod_clientes }}">
                                {{ $cli->nom_cli }} {{ $cli->app_cli }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Vehículo</label>
                    <select name="cod_vehiculos_sol" id="selectVehiculo" required>
                        <option value="">Seleccione primero un cliente...</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tipo de mantenimiento</label>
                    <select name="tma_sol" required>
                        <option value="Mantenimiento Preventivo">Mantenimiento Preventivo</option>
                        <option value="Mantenimiento Correctivo">Mantenimiento Correctivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="est_sol" required>
                        <option value="Pendiente">Pendiente</option>
                        <option value="En_Proceso">En Proceso</option>
                        <option value="Finalizado">Finalizado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Servicio solicitado</label>
                    <input type="text" name="ser_sol"
                           placeholder="Ej: Cambio de aceite, revisión frenos...">
                </div>

                <div class="form-group">
                    <label>Fecha de solicitud</label>
                    <input type="date" name="fec_sol" required>
                </div>

                <div class="form-group">
                    <label>Fecha programada</label>
                    <input type="date" name="fpr_sol">
                </div>

                <div class="form-group">
                    <label>Hora programada</label>
                    <input type="time" name="hpr_sol">
                </div>

                <div class="form-group" style="grid-column:1/3;">
                    <label>Observación</label>
                    <textarea name="obs_sol" rows="3"
                              placeholder="Observaciones adicionales..."></textarea>
                </div>

            </div>

            <div class="modal-botones">
                <button type="submit" class="btn-guardar-modal">Guardar</button>
                <button type="button" class="btn-cancelar-modal"
                        onclick="cerrarModalSolicitud()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════
     MODAL — ASIGNAR TÉCNICO
     Coloca este bloque FUERA del div principal, junto a los otros modales.
══════════════════════════════════════════════════════════════════ --}}
<div id="modalAsignar"
     style="display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.65); z-index:2000;
            justify-content:center; align-items:center;">
 
    <div class="modal-asignar-box">
 
        {{-- Header --}}
        <div class="modal-asignar-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <span class="material-symbols-outlined" style="color:#ff7b00;font-size:26px;">engineering</span>
                <h2 style="margin:0;color:#ff7b00;font-size:18px;font-weight:700;">Asignar Técnico</h2>
            </div>
            <span class="cerrar-modal" onclick="cerrarModalAsignar()"
                  style="font-size:26px;cursor:pointer;color:#ccc;line-height:1;">&times;</span>
        </div>
 
        <form id="formAsignar"
              action="{{ route('asignaciones.guardar') }}"
              method="POST">
            @csrf
 
            {{-- Campo oculto: cod_solicitudes --}}
            <input type="hidden" name="cod_solicitudes_asi" id="asi_cod_sol">
 
            {{-- Referencia visual al código de solicitud --}}
            <div class="asi-info-row">
                <span class="material-symbols-outlined" style="color:#ff7b00;font-size:20px;">assignment</span>
                <div>
                    <p class="asi-label">Solicitud</p>
                    <p class="asi-value" id="asi_cod_sol_display">—</p>
                </div>
            </div>
 
            {{-- Fecha de asignación (solo lectura) --}}
            <div class="asi-info-row">
                <span class="material-symbols-outlined" style="color:#ff7b00;font-size:20px;">event</span>
                <div>
                    <p class="asi-label">Fecha de asignación</p>
                    <p class="asi-value" id="asi_fecha_display">—</p>
                </div>
            </div>
 
            <div style="height:1px;background:#3a3a3a;margin:16px 0;"></div>
 
            {{-- Técnico --}}
            <div class="asi-form-group">
                <label for="asi_tecnico">
                    <span class="material-symbols-outlined"
                          style="font-size:16px;vertical-align:middle;color:#ff7b00;">person_pin</span>
                    Técnico asignado <span style="color:#dc3545;">*</span>
                </label>
                <select name="cod_usuarios_asi" id="asi_tecnico" required>
                    <option value="">Seleccione un técnico...</option>
                    @foreach($tecnicos as $tec)
                        <option value="{{ $tec->cod_usuarios }}">
                            {{ $tec->nom_usu }} {{ $tec->app_usu ?? '' }}
                        </option>
                    @endforeach
                </select>
            </div>
 
            {{-- Observación (heredada, editable) --}}
            <div class="asi-form-group">
                <label for="asi_obs">
                    <span class="material-symbols-outlined"
                          style="font-size:16px;vertical-align:middle;color:#ff7b00;">notes</span>
                    Observación
                </label>
                <textarea name="obs_asi" id="asi_obs" rows="3"
                          placeholder="Observaciones heredadas de la solicitud..."></textarea>
            </div>
 
            {{-- Botones --}}
            <div class="asi-botones">
                <button type="button" class="btn-asi-cancelar" onclick="cerrarModalAsignar()">
                    Cancelar
                </button>
                <button type="submit" class="btn-asi-guardar">
                    <span class="material-symbols-outlined"
                          style="font-size:15px;vertical-align:middle;">check_circle</span>
                    Confirmar asignación
                </button>
            </div>
 
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════
     ESTILOS
══════════════════════════════════════════ --}}
<style>
    /* ── Pestañas ── */
    .tabs-seccion {
        display: flex;
        gap: 4px;
        margin-bottom: 20px;
        border-bottom: 2px solid #3a3a3a;
    }
    .tab-btn {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #aaa;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: -2px;
        transition: all 0.2s;
    }
    .tab-btn:hover  { color: #fff; }
    .tab-btn.active { color: #ff7b00; border-bottom-color: #ff7b00; }

    .tab-count {
        background: #3a3a3a;
        color: #ccc;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 600;
    }
    .tab-count.has-items { background: #dc3545; color: #fff; }

    /* ── Banner papelera ── */
    .papelera-header {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(220,53,69,0.08);
        border: 1px solid rgba(220,53,69,0.25);
        border-radius: 8px;
        padding: 10px 16px;
        margin-bottom: 16px;
        color: #f08090;
        font-size: 13px;
    }

    /* ── Filtros ── */
    .filtros-estado { display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap; }
    .filtro-btn {
        padding: 6px 18px;
        border-radius: 20px;
        border: 1px solid #555;
        background: #2a2a2a;
        color: #ccc;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
    }
    .filtro-btn:hover  { border-color: #ff7b00; color: #ff7b00; }
    .filtro-btn.active { background: #ff7b00; color: #fff; border-color: #ff7b00; font-weight: bold; }

    /* ── Tabla papelera atenuada ── */
    #tablaPapelera tbody tr         { opacity: 0.8; }
    #tablaPapelera tbody tr:hover   { opacity: 1; }

    /* ── Botón restaurar ── */
    .btn-restaurar {
        background: #1a3a26;
        color: #4caf50;
        border: 1px solid #2d6a3a;
        padding: 5px 12px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
    }
    .btn-restaurar:hover { background: #28a745; color: #fff; border-color: #28a745; }

    /* ── Modales de confirmación ── */
    .modal-confirm-box {
        background: #2a2a2a;
        border: 2px solid #444;
        border-radius: 12px;
        padding: 36px 40px;
        width: 420px;
        max-width: 94vw;
        text-align: center;
    }
    .modal-confirm-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(220,53,69,0.12);
        border: 2px solid rgba(220,53,69,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
    }
    .modal-confirm-icon .material-symbols-outlined { font-size:32px; color:#dc3545; }
    .modal-confirm-icon.restaurar {
        background: rgba(40,167,69,0.12);
        border-color: rgba(40,167,69,0.35);
    }
    .modal-confirm-icon.restaurar .material-symbols-outlined { color:#28a745; }

    .modal-confirm-titulo { color:#fff; font-size:18px; margin:0 0 12px; font-weight:600; }
    .modal-confirm-texto  { color:#ccc; font-size:14px; line-height:1.6; margin:0 0 28px; }

    .modal-confirm-botones { display:flex; gap:12px; justify-content:center; }

    .btn-confirm-cancelar {
        background:#3a3a3a; color:#ccc; border:1px solid #555;
        padding:10px 26px; border-radius:8px; cursor:pointer; font-size:14px; transition:all 0.2s;
    }
    .btn-confirm-cancelar:hover { background:#444; color:#fff; }

    .btn-confirm-eliminar {
        background:#dc3545; color:#fff; border:none;
        padding:10px 26px; border-radius:8px; cursor:pointer;
        font-size:14px; font-weight:600; transition:all 0.2s;
    }
    .btn-confirm-eliminar:hover { background:#c82333; }

    .btn-confirm-restaurar {
        background:#28a745; color:#fff; border:none;
        padding:10px 26px; border-radius:8px; cursor:pointer;
        font-size:14px; font-weight:600; transition:all 0.2s;
    }
    .btn-confirm-restaurar:hover { background:#218838; }

    /* ── Badges y estilos heredados ── */
    .obs-col { max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

    .badge-tipo {
        padding:3px 10px; border-radius:8px; font-size:12px; font-weight:600;
        border:1px solid #ff7b00; color:#ff7b00; background:transparent; white-space:nowrap;
    }
    .estado { padding:4px 10px; border-radius:10px; font-size:12px; font-weight:600; white-space:nowrap; }
    .estado.pendiente  { background-color:#6c757d; color:#fff; }
    .estado.en-proceso { background-color:#ffc107; color:#000; }
    .estado.finalizado { background-color:#28a745; color:#fff; }
    .estado.cancelado  { background-color:#dc3545; color:#fff; }

    .id-usuario       { gap:10px; }
    .icono-perfil     { font-size:30px; color:#ff7b00; vertical-align:middle; }
    .perfil-conductor { display:flex; align-items:center; gap:8px; }
    .buscador         { color:#000; }
    .select-estado    { border:1px solid #ccc; border-radius:6px; padding:4px 8px; font-size:13px; }

    /* ── Modal ABM ── */
    #modalSolicitud .modal-contenido-sol {
        background:#2a2a2a; border:2px solid #ff7b00; border-radius:10px;
        padding:30px; width:700px; max-width:95vw; max-height:90vh; overflow-y:auto;
    }
    #modalSolicitud .modal-header {
        display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;
    }
    #modalSolicitud .modal-header h2 { color:#ff7b00; margin:0; font-size:20px; }
    #modalSolicitud .cerrar-modal { font-size:26px; cursor:pointer; color:#ccc; line-height:1; }
    #modalSolicitud .cerrar-modal:hover { color:#fff; }
    #modalSolicitud .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
    #modalSolicitud .form-group { display:flex; flex-direction:column; gap:6px; }
    #modalSolicitud .form-group label { color:#ccc; font-size:13px; font-weight:500; }
    #modalSolicitud .form-group input,
    #modalSolicitud .form-group select,
    #modalSolicitud .form-group textarea {
        width:100%; padding:8px 12px; background:#3a3a3a; border:1px solid #555;
        border-radius:6px; color:#fff; font-size:14px; box-sizing:border-box;
    }
    #modalSolicitud .form-group textarea { resize:vertical; }
    #modalSolicitud .modal-botones { display:flex; justify-content:flex-end; gap:12px; margin-top:24px; }
    #modalSolicitud .btn-guardar-modal {
        background:#ff7b00; color:#fff; border:none; padding:10px 28px;
        border-radius:6px; cursor:pointer; font-weight:bold; font-size:14px;
    }
    #modalSolicitud .btn-guardar-modal:hover { background:#e06a00; }
    #modalSolicitud .btn-cancelar-modal {
        background:#555; color:#fff; border:none; padding:10px 28px;
        border-radius:6px; cursor:pointer; font-size:14px;
    }
    #modalSolicitud .btn-cancelar-modal:hover { background:#444; }

    .btn-reporte {
        background: transparent;
        color: #ff7b00;
        border: 1px solid #ff7b00;
        padding: 8px 18px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        margin-left: 10px;
    }
    .btn-reporte:hover { background: #ff7b00; color: #fff; }

    {{-- ══════════════════════════════════════════
     ESTILOS — pégalos dentro del bloque <style> existente
══════════════════════════════════════════ --}}
/* ── Botón Asignar ── */
.btn-asignar {
    background: transparent; color: #1D9E75; border: 1px solid #1D9E75;
    padding: 5px 12px; border-radius: 6px; cursor: pointer;
    font-size: 12px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;
}
.btn-asignar:hover { background: #1D9E75; color: #fff; }
 
/* ── Modal Asignar ── */
.modal-asignar-box {
    background: #2a2a2a;
    border: 2px solid #ff7b00;
    border-radius: 12px;
    padding: 28px 32px;
    width: 480px;
    max-width: 95vw;
    max-height: 90vh;
    overflow-y: auto;
}
.modal-asignar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}
 
/* Filas de info (solo lectura) */
.asi-info-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #1e1e1e;
    border: 1px solid #3a3a3a;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 10px;
}
.asi-label { color: #888; font-size: 11px; margin: 0 0 3px; text-transform: uppercase; letter-spacing: .5px; }
.asi-value { color: #fff; font-size: 14px; font-weight: 600; margin: 0; }
 
/* Campos del form */
.asi-form-group { display: flex; flex-direction: column; gap: 7px; margin-bottom: 16px; }
.asi-form-group label { color: #ccc; font-size: 13px; font-weight: 500; display: flex; align-items: center; gap: 5px; }
.asi-form-group select,
.asi-form-group textarea {
    width: 100%; padding: 9px 12px; background: #3a3a3a; border: 1px solid #555;
    border-radius: 6px; color: #fff; font-size: 14px; box-sizing: border-box;
}
.asi-form-group select:focus,
.asi-form-group textarea:focus { outline: none; border-color: #ff7b00; }
.asi-form-group textarea { resize: vertical; }
 
/* Botones del modal */
.asi-botones { display: flex; justify-content: flex-end; gap: 12px; margin-top: 8px; }
.btn-asi-cancelar {
    background: #3a3a3a; color: #ccc; border: 1px solid #555;
    padding: 10px 22px; border-radius: 8px; cursor: pointer; font-size: 14px;
    transition: all 0.2s;
}
.btn-asi-cancelar:hover { background: #444; color: #fff; }
.btn-asi-guardar {
    background: #ff7b00; color: #fff; border: none;
    padding: 10px 22px; border-radius: 8px; cursor: pointer;
    font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.2s;
}
.btn-asi-guardar:hover { background: #e06a00; }

.btn-eliminar {
    background: transparent; color: #E24B4A; border: 1px solid #E24B4A;
    padding: 5px 12px; border-radius: 6px; cursor: pointer;
    font-size: 12px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;
}
.btn-eliminar:hover { background: #E24B4A; color: #fff; }

.btn-editar {
    background: transparent; color: #378ADD; border: 1px solid #378ADD;
    padding: 5px 12px; border-radius: 6px; cursor: pointer;
    font-size: 12px; font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;
}
.btn-editar:hover { background: #378ADD; color: #fff; }

</style>

{{-- ══════════════════════════════════════════
     JAVASCRIPT
══════════════════════════════════════════ --}}
<script>
/* ── Pestañas ── */
function cambiarTab(tab, btn) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('seccion-activas').style.display  = tab === 'activas'  ? '' : 'none';
    document.getElementById('seccion-papelera').style.display = tab === 'papelera' ? '' : 'none';
}

/* ── Modal eliminar ── */
let _idEliminar = null;

function confirmarEliminar(id, nombreCliente) {
    _idEliminar = id;
    document.getElementById('confirmNombreCliente').textContent =
        nombreCliente.trim() || 'este cliente';
    document.getElementById('modalConfirmarEliminar').style.display = 'flex';
}
function cerrarModalConfirmar() {
    _idEliminar = null;
    document.getElementById('modalConfirmarEliminar').style.display = 'none';
}
function ejecutarEliminar() {
    if (!_idEliminar) return;
    const form = document.getElementById('formEliminar');
    form.action = `/dashboard/solicitudes/${_idEliminar}/eliminar`;
    form.style.display = 'block';
    form.submit();
}

/* ── Modal restaurar ── */
let _idRestaurar = null;

function confirmarRestaurar(id, nombreCliente) {
    _idRestaurar = id;
    document.getElementById('confirmNombreRestaurar').textContent =
        nombreCliente.trim() || 'este cliente';
    document.getElementById('modalConfirmarRestaurar').style.display = 'flex';
}
function cerrarModalRestaurar() {
    _idRestaurar = null;
    document.getElementById('modalConfirmarRestaurar').style.display = 'none';
}
function ejecutarRestaurar() {
    if (!_idRestaurar) return;
    const form = document.getElementById('formRestaurar');
    form.action = `/dashboard/solicitudes/${_idRestaurar}/restaurar`;
    form.style.display = 'block';
    form.submit();
}

/* ── Cerrar modales al click fuera ── */
window.addEventListener('click', e => {
    if (e.target === document.getElementById('modalConfirmarEliminar'))  cerrarModalConfirmar();
    if (e.target === document.getElementById('modalConfirmarRestaurar')) cerrarModalRestaurar();
    if (e.target === document.getElementById('modalSolicitud'))          cerrarModalSolicitud();
});

/* ── Modal ABM ── */
function abrirModalSolicitud() {
    const form = document.getElementById('formSolicitud');
    form.reset();
    form.action = '{{ route('solicitudes.guardar') }}';
    document.getElementById('methodSol').value = 'POST';
    document.getElementById('selectCliente').disabled = false;
    document.getElementById('selectVehiculo').innerHTML =
        '<option value="">Seleccione primero un cliente...</option>';
    document.getElementById('tituloModalSol').innerText = 'Registrar Solicitud';
    document.querySelector('input[name="fec_sol"]').value =
        new Date().toISOString().split('T')[0];
    document.getElementById('modalSolicitud').style.display = 'flex';
}
function cerrarModalSolicitud() {
    document.getElementById('modalSolicitud').style.display = 'none';
}

/* ── Vehículos dinámicos ── */
async function cargarVehiculos(codCliente, seleccionado = '') {
    const sel = document.getElementById('selectVehiculo');
    sel.innerHTML = '<option value="">Cargando...</option>';
    if (!codCliente) {
        sel.innerHTML = '<option value="">Seleccione primero un cliente...</option>';
        return;
    }
    try {
        const resp = await fetch(`/dashboard/solicitudes/vehiculos/${codCliente}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        const vehiculos = await resp.json();
        sel.innerHTML = '<option value="">Seleccione un vehículo...</option>';
        vehiculos.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v.cod_vehiculos;
            opt.textContent = `${v.mar_veh} ${v.mod_veh}` + (v.ani_veh ? ` (${v.ani_veh})` : '');
            if (v.cod_vehiculos === seleccionado) opt.selected = true;
            sel.appendChild(opt);
        });
    } catch (e) {
        sel.innerHTML = '<option value="">Error al cargar vehículos</option>';
    }
}

/* ── Editar solicitud ── */
async function editarSolicitud(
    id, codCliente, codVehiculo, tipo, estado,
    obs, servicio, fecSol, fprSol, hprSol
) {
    abrirModalSolicitud();
    const form = document.getElementById('formSolicitud');
    form.action = `/dashboard/solicitudes/${id}/actualizar`;
    document.getElementById('methodSol').value = 'PUT';
    document.getElementById('tituloModalSol').innerText = 'Editar Solicitud';
    document.getElementById('selectCliente').value = codCliente;
    await cargarVehiculos(codCliente, codVehiculo);
    document.querySelector('select[name="tma_sol"]').value   = tipo;
    document.querySelector('select[name="est_sol"]').value   = estado;
    document.querySelector('textarea[name="obs_sol"]').value = obs      ?? '';
    document.querySelector('input[name="ser_sol"]').value    = servicio  ?? '';
    document.querySelector('input[name="fec_sol"]').value    = fecSol   ?? '';
    document.querySelector('input[name="fpr_sol"]').value    = fprSol   ?? '';
    document.querySelector('input[name="hpr_sol"]').value    = hprSol   ?? '';
}

/* ── Guardar estado inline (AJAX) ── */
async function guardarEstadoSolicitud(boton) {
    const fila   = boton.closest('tr');
    const id     = fila.dataset.id;
    const select = fila.querySelector('.estado-select');
    const span   = fila.querySelector('.estado');
    const csrf   = document.querySelector('meta[name="csrf-token"]').content;
    try {
        const resp = await fetch(`/dashboard/solicitudes/${id}/estado`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept':       'application/json'
            },
            body: JSON.stringify({ est_sol: select.value })
        });
        const data = await resp.json();
        if (data.success) {
            span.textContent     = select.value;
            span.className       = 'estado ' + select.value.toLowerCase().replace(/_/g, '-');
            span.style.display   = 'inline-block';
            select.style.display = 'none';
            fila.querySelector('.btn-guardar').style.display = 'none';
            fila.querySelector('.btn-editar').style.display  = 'inline-block';
            fila.dataset.estado = select.value;
            mostrarAviso('✅ Estado actualizado', 'success');
        } else {
            mostrarAviso('❌ Error al actualizar', 'error');
        }
    } catch (e) {
        mostrarAviso('⚠️ Error de conexión', 'error');
    }
}

/* ── Filtros de estado ── */
document.querySelectorAll('.filtro-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const estado = this.dataset.estado;
        const term   = document.getElementById('searchInput').value.toLowerCase().trim();
        document.querySelectorAll('#tablaSolicitudes tbody tr').forEach(fila => {
            const ok = (estado === 'TODOS' || fila.dataset.estado === estado)
                    && (term === '' || fila.textContent.toLowerCase().includes(term));
            fila.style.display = ok ? '' : 'none';
        });
    });
});

/* ── Buscador en tiempo real ── */
document.getElementById('searchInput').addEventListener('input', function () {
    const term         = this.value.toLowerCase().trim();
    const estadoActivo = document.querySelector('.filtro-btn.active')?.dataset.estado ?? 'TODOS';
    document.querySelectorAll('#tablaSolicitudes tbody tr').forEach(fila => {
        const ok = (estadoActivo === 'TODOS' || fila.dataset.estado === estadoActivo)
                && (term === '' || fila.textContent.toLowerCase().includes(term));
        fila.style.display = ok ? '' : 'none';
    });
});

/* ── Toast ── */
function mostrarAviso(msg, tipo) {
    const aviso = document.createElement('div');
    aviso.textContent = msg;
    Object.assign(aviso.style, {
        position:'fixed', bottom:'20px', right:'20px',
        padding:'12px 18px', borderRadius:'8px', fontWeight:'bold',
        color:           tipo === 'success' ? '#fff' : '#000',
        backgroundColor: tipo === 'success' ? '#00c851' : '#ffbb33',
        boxShadow:'0 0 10px rgba(0,0,0,0.3)', zIndex:9999,
        transition:'opacity 0.5s ease'
    });
    document.body.appendChild(aviso);
    setTimeout(() => aviso.style.opacity = '0', 2000);
    setTimeout(() => aviso.remove(), 2500);
}

/* ── Modal Asignar ── */
function abrirModalAsignar(codSolicitud, observacion) {
    // Rellena el campo oculto y el display del código
    document.getElementById('asi_cod_sol').value       = codSolicitud;
    document.getElementById('asi_cod_sol_display').textContent = codSolicitud;
 
    // Fecha de hoy formateada dd/mm/aaaa (solo visual, la BD usa CURRENT_DATE)
    const hoy = new Date();
    const dd  = String(hoy.getDate()).padStart(2, '0');
    const mm  = String(hoy.getMonth() + 1).padStart(2, '0');
    const aa  = hoy.getFullYear();
    document.getElementById('asi_fecha_display').textContent = `${dd}/${mm}/${aa}`;
 
    // Hereda la observación de la solicitud
    document.getElementById('asi_obs').value = observacion ?? '';
 
    // Resetea el select de técnico
    document.getElementById('asi_tecnico').value = '';
 
    document.getElementById('modalAsignar').style.display = 'flex';
}
 
function cerrarModalAsignar() {
    document.getElementById('modalAsignar').style.display = 'none';
}
 
// Cierra al hacer click fuera del modal
window.addEventListener('click', e => {
    if (e.target === document.getElementById('modalAsignar')) cerrarModalAsignar();
});

</script>

@endsection