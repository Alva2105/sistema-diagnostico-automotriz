@extends('layouts.dashboard')

@section('contenido')

<div class="dash-home">

    <!-- ══════════════════════════════════════════
         HEADER
    ══════════════════════════════════════════ -->
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Panel de Control</h1>
            <p class="dash-sub">Bienvenido, {{ auth()->user()->nom_usu }} — {{ now()->format('d/m/Y') }}</p>
        </div>
        <div class="dash-logo-corner">
            <img src="{{ asset('assets/img/logos/jhire.png') }}" alt="JHIRE">
        </div>
    </div>

    <!-- ══════════════════════════════════════════
         TARJETAS DE ESTADÍSTICAS
    ══════════════════════════════════════════ -->
    <div class="dash-cards">

        <div class="dash-card orange">
            <div class="dash-card-icon">
                <span class="material-symbols-outlined">person</span>
            </div>
            <div class="dash-card-info">
                <div class="dash-card-num">{{ $totalClientes }}</div>
                <div class="dash-card-label">Clientes Registrados</div>
            </div>
            <a href="{{ route('usuarios.clientes') }}" class="dash-card-link">Ver todos →</a>
        </div>

        <div class="dash-card dark">
            <div class="dash-card-icon">
                <span class="material-symbols-outlined">engineering</span>
            </div>
            <div class="dash-card-info">
                <div class="dash-card-num">{{ $totalTecnicos }}</div>
                <div class="dash-card-label">Técnicos Registrados</div>
            </div>
            <a href="{{ route('usuarios.tecnicos') }}" class="dash-card-link">Ver todos →</a>
        </div>

        <div class="dash-card orange">
            <div class="dash-card-icon">
                <span class="material-symbols-outlined">directions_car</span>
            </div>
            <div class="dash-card-info">
                <div class="dash-card-num">{{ $totalVehiculos }}</div>
                <div class="dash-card-label">Vehículos Registrados</div>
            </div>
            <a href="{{ route('dashboard.vehiculos') }}" class="dash-card-link">Ver todos →</a>
        </div>

        <div class="dash-card dark">
            <div class="dash-card-icon">
                <span class="material-symbols-outlined">assignment</span>
            </div>
            <div class="dash-card-info">
                <div class="dash-card-num">{{ $solicitudesPendientes }}</div>
                <div class="dash-card-label">Solicitudes Pendientes</div>
            </div>
            <a href="{{ route('dashboard.solicitudes') }}" class="dash-card-link">Ver todas →</a>
        </div>

    </div>

    <!-- ══════════════════════════════════════════
         FILA INFERIOR: ÚLTIMAS SOLICITUDES + ÚLTIMOS USUARIOS
    ══════════════════════════════════════════ -->
    <div class="dash-bottom">

        <!-- Últimas solicitudes -->
        <div class="dash-panel">
            <div class="dash-panel-header">
                <span class="material-symbols-outlined">assignment</span>
                Últimas Solicitudes
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimasSolicitudes as $sol)
                    <tr>
                        <td>{{ $sol->cod_solicitudes }}</td>
                        <td>{{ $sol->cliente->nom_cli ?? '—' }} {{ $sol->cliente->app_cli ?? '' }}</td>
                        <td>
                            <span class="dash-badge {{ strtolower($sol->tma_sol) }}">
                                {{ $sol->tma_sol }}
                            </span>
                        </td>
                        <td>
                            <span class="dash-badge {{ strtolower(str_replace('_','-',$sol->est_sol)) }}">
                                {{ $sol->est_sol }}
                            </span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($sol->fec_sol)->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="dash-empty">Sin solicitudes recientes</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <a href="{{ route('dashboard.solicitudes') }}" class="dash-panel-footer">Ver todas las solicitudes →</a>
        </div>

        <!-- Últimos usuarios registrados -->
        <div class="dash-panel">
            <div class="dash-panel-header">
                <span class="material-symbols-outlined">group_add</span>
                Últimos Usuarios Registrados
            </div>
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ultimosUsuarios as $usr)
                    <tr>
                        <td>{{ $usr->cod_usuarios }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:8px;">
                                @if($usr->img_usu)
                                    <img src="{{ asset('storage/'.$usr->img_usu) }}"
                                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:2px solid #ff7b00;">
                                @else
                                    <span class="material-symbols-outlined" style="color:#ff7b00;font-size:32px;">account_circle</span>
                                @endif
                                {{ $usr->nom_usu }} {{ $usr->app_usu }}
                            </div>
                        </td>
                        <td>{{ $usr->email_usu }}</td>
                        <td>
                            <span class="dash-badge rol">{{ $usr->rol->nom_rol ?? '—' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="dash-empty">Sin usuarios recientes</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <a href="{{ route('dashboard.usuarios') }}" class="dash-panel-footer">Ver todos los usuarios →</a>
        </div>

    </div>

</div>

@endsection