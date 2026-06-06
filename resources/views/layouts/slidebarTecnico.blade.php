<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Técnico')</title>
    <link rel="icon" href="{{ asset('favicons/jhire.ico') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="slidebar-tecnico">

    <!-- ======== GRID PRINCIPAL ======== -->
    <div class="layout">

        {{-- ========== SIDEBAR ========== --}}
        <aside class="sidebar-Tecnico">

            <div class="logo">
                <img src="{{ asset('assets/img/logos/jhire.png') }}" alt="JHIRE">
            </div>

            <nav class="menu-Tecnico">
                @php
                    // Estás en una ruta de mantenimientos (listados o seguimiento)
                    $isMaintenanceRoute = request()->routeIs('tecnico.mantenimientos*') || request()->routeIs('tecnico.seguimiento*');

                    // Sólo usamos / leemos el origen cuando estamos en rutas de mantenimientos.
                    // En cualquier otra página se deja null para que no marque ninguna sub-opción.
                    $origen = $isMaintenanceRoute ? ($origen ?? session('origen_mantenimiento', 'asignados')) : null;
                @endphp

                <li class="tech-has-submenu tech-open">
                    {{-- Nota: dejamos tech-open siempre para que el submenú esté visible --}}
                    <button class="tech-subtoggle" type="button" aria-expanded="{{ $isMaintenanceRoute ? 'true' : 'false' }}">
                        <i class="material-symbols-outlined">build_circle</i>
                        Mis Mantenimientos
                    </button>
                
                    <ul class="tech-submenu">
                        <li class="tech-subitem">
                            <a href="{{ route('tecnico.asignaciones') }}"
                                class="tech-sublink {{ request()->routeIs('tecnico.asignaciones') ? 'tech-active-sub' : '' }}">
                                <i class="material-symbols-outlined tiny">assignment</i>
                                Asignados
                            </a>

                        </li>
                    
                        <li class="tech-subitem">
                            <a href="{{ route('tecnico.finalizados') }}"
                                class="tech-sublink {{ ($origen === 'finalizados') ? 'tech-active-sub' : '' }}">
                                <i class="material-symbols-outlined tiny">check_circle</i>
                                Finalizados
                            </a>
                        </li>
                    </ul>
                </li>
            {{-- 
                <a href=href="{{ route('tecnico.reportes') }}"
                    class="menu__item {{ request()->routeIs('tecnico.solicitudes') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">assignment</i>
                    <span class="menu__tit">Mis Reportes</span>
                </a>
            --}}
            </nav>

            <div class="dashboard-user-actions">
                <a href="{{ route('tecnico.perfil') }}"
                    class="menu__item {{ request()->routeIs('tecnico.perfil') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">account_circle</i>
                    <span class="menu__tit">Mi Perfil</span>
                </a>

                <a href="#" class="dashboard-link logout-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-symbols-outlined">logout</i>
                    Cerrar Sesión
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </aside>


        {{-- ========== CONTENIDO PRINCIPAL ========== --}}
        <main class="content-area">
            @yield('content')
        </main>

    </div> <!-- /layout -->
    @stack('scripts')
</body>
</html>
