<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHIRE MOTORS | Taller Automotriz Jhire</title>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">

    <!-- Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <!-- Header global -->
    <x-header />

    <!-- === SECCIÓN WELCOME === -->
    <section class="video-container">
        <video class="bg-video" autoplay muted loop playsinline preload="auto">
            <source src="{{ asset('assets/video/Video Intro.mp4') }}" type="video/mp4">
        </video>
        <div class="overlay" aria-hidden="true"></div>

        <div class="titulo-content" role="main">
            <h1 class="titulo">JHIRE</h1>
            <p class="subtitulo-welcome">MULTISERVICIO AUTOMOTRIZ</p>
        </div>
    </section>

    <!-- === SECCIÓN TIPO DE MANTENIMIENTO === -->
    <main class="main-content relative">
        <div class="services-container">
            {{-- PANEL PREVENTIVO --}}
            <div class="service-panel preventive" id="panel-preventivo">
                <div class="panel-image"
                    style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                    url('{{ asset('assets/img/generales/Mantenimiento Preventivo.png') }}');">

                    <video class="hover-video" preload="auto" playsinline muted loop>
                        <source src="{{ asset('assets/video/mantPreventivo.mp4') }}" type="video/mp4">
                    </video>

                    <div class="image-overlay">
                        <h2 class="mant-title">
                            <span class="title-line1">MANTENIMIENTO</span>
                            <span class="title-line2">PREVENTIVO</span>
                        </h2>
                    </div>
                </div>

                <button class="services-btn" id="btnPreventivo" data-url="{{ route('servicios.preventivo') }}">
                    SERVICIOS
                </button>
            </div>

            {{-- PANEL CORRECTIVO --}}
            <div class="service-panel corrective" id="panel-correctivo">
                <div class="panel-image"
                    style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                    url('{{ asset('assets/img/generales/Mantenimiento Correctivo.png') }}');">

                    <video class="hover-video" preload="auto" playsinline muted loop>
                        <source src="{{ asset('assets/video/mantCorrectivo.mp4') }}" type="video/mp4">
                    </video>

                    <div class="image-overlay">
                        <h2 class="mant-title">
                            <span class="title-line1">MANTENIMIENTO</span>
                            <span class="title-line2">CORRECTIVO</span>
                        </h2>
                    </div>
                </div>

                <button class="services-btn" id="btnCorrectivo" data-url="{{ route('servicios.correctivo') }}">
                    SERVICIOS
                </button>
            </div>

            {{-- CONTENEDOR AJAX --}}
            <div id="servicio-container"></div>
        </div>
    </main>
    <!-- Footer global -->
    <x-footer />
@stack('scripts')
@php
    $isLogged = Auth::check();
@endphp

<script>
    window.solicitudConfig = {
        isLogged: @json($isLogged),
        loginRoute: "{{ route('login') }}",
        solicitudRoute: "{{ route('solicitud.crear') }}"
    };
</script>

@vite(['resources/js/solicitud.js'])
</body>
</html>