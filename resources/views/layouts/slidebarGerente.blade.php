<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Gerente')</title>
    <link rel="icon" href="{{ asset('favicons/jhire.ico') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('style')
</head>

<body class="slidebar-gerente">

    <!-- ======== GRID PRINCIPAL ======== -->
    <div class="layout">

        {{-- ========== SIDEBAR ========== --}}
        <aside class="sidebar-Gerente">

            <div class="logo">
                <img src="{{ asset('assets/img/logos/jhire.png') }}" alt="JHIRE">
            </div>

            <div class="menu-Gerente">
            {{-- 
                <a href="{{ route('gerente.home') }}"
                    class="menu__item {{ request()->routeIs('gerente.home') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">home</i>
                    <span class="menu__tit">Panel Principal</span>
                </a>
            --}}
                <a href="{{ route('gerente.inventarios') }}"
                    class="menu__item {{ request()->is('gerente/inventari*') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">inventory_2</i>
                    <span class="menu__tit">Inventarios</span>
                </a>
                <a href="{{ route('gerente.solicitudes') }}"
                    class="menu__item {{ request()->is('gerente/solicitud*') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">assignment</i>
                    <span class="menu__tit">Solicitudes</span>
                </a>
                <a href="{{ route('gerente.kardexClientes') }}"
                    class="menu__item {{ request()->routeIs('gerente.kardexClientes') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">assignment_ind</i>
                    <span class="menu__tit">Kardex de Clientes</span>
                </a>
                <a href="{{ route('gerente.listadoTecnicos') }}"
                    class="menu__item 
                        {{ request()->routeIs('gerente.listadoTecnicos') 
                            || request()->routeIs('gerente.tecnico.reportes') 
                            || request()->routeIs('gerente.mantenimiento.detalle')
                            ? 'menu__item--active' 
                            : '' }}">
                    <i class="material-symbols-outlined">clinical_notes</i>
                    <span class="menu__tit">Reportes de Técnicos</span>
                </a>
            </div>

            <div class="dashboard-user-actions">
                <a href="{{ route('gerente.perfil') }}"
                    class="menu__item {{ request()->routeIs('gerente.perfil') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">account_circle</i>
                    <span class="menu__tit">Mi Perfil</span>
                </a>
            {{-- 
                <a href="{{ route('gerente.notifiGerente') }}"
                    class="menu__item {{ request()->routeIs('gerente.notifiGerente') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">notifications</i>
                    <span class="menu__tit">Notificaciones</span>
                </a>
            --}}
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
        <main class="content-area @yield('main-class', '')" data-header-height="{{ config('app.report_header_height', 200) }}">
            @yield('content')
        </main>

    </div> <!-- /layout -->
    @stack('scripts')
</body>
</html>
