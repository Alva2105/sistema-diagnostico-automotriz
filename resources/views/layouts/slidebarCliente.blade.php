<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cliente')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">
        <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div class="layout">
        <aside class="sidebar-Cliente">
            <div class="logo">
                <img src="{{ asset('assets/img/logos/jhire.png') }}" alt="JHIRE" class="logo img">
            </div>
            <nav class="menu-Cliente">
                <a href="{{ route('welcome') }}" class="menu__item {{ request()->routeIs('welcome') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">home</i>
                    <span class="menu__tit">Inicio</span>
                </a>
            
                <a href="{{ route('cliente.vehiculos') }}" class="menu__item {{ request()->routeIs('cliente.vehiculos') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">directions_car</i>
                    <span class="menu__tit">Mis Vehículos</span>
                </a>
            
                <a href="{{ route('cliente.solicitudes') }}" class="menu__item {{ request()->routeIs('cliente.solicitudes') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">assignment</i>
                    <span class="menu__tit">Mis Solicitudes</span>
                </a>
            {{-- 
                <a href="#" class="menu__item" onclick="return false;">
                    <i class="material-symbols-outlined">assignment</i>
                    <span class="menu__tit2">Historial de Mantenimientos</span>
                </a>
            --}}
            </nav>

            <div class="dashboard-user-actions">
                <a href="{{ route('cliente.perfil') }}" class="menu__item {{ request()->routeIs('cliente.perfil') ? 'menu__item--active' : '' }}">
                    <i class="material-symbols-outlined">account_circle</i>
                    <span class="menu__tit">Mi Perfil</span>
                </a>

                <a href="#" 
                    class="dashboard-link logout-link"
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
        <main class="content-area-cliente">
            @yield('content')
        </main>

    </div> <!-- /layout -->
    @stack('scripts')
</body>
</html>