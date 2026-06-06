<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JHIRE - Iniciar Sesión</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div class="login-container">
        <div class="background"></div>
        
        <a href="{{ route('welcome') }}" class="back-link">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle;">arrow_back</span>
            Volver
        </a>
        
        <div class="form-box">
            <h1 class="title-log">INICIAR SESIÓN</h1>

            {{-- Mensaje de estado (por ejemplo, cierre de sesión exitoso) --}}
            @if (session('status'))
                <div class="success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="Login-input-container">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Correo Electrónico" required autofocus>
                    @error('email')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="Login-input-container">
                    <input type="password" name="password" placeholder="Contraseña" required>
                    @error('password')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="options">
                    <label class="remember-label">
                        <input type="checkbox" name="remember">
                        Recordarme
                    </label>
                    <a href="#" class="forgot-link">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
                @if(request()->has('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif
                <button type="submit" class="btn-confirm">Confirmar</button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">
                    ¿No tienes cuenta? 
                    <a href="{{ route('register') }}" style="color: #ffb300; text-decoration: none; font-weight: bold;">
                        Regístrate aquí
                    </a>
                </p>
            </div>
        </div>
    </div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Al enviar el formulario de login
    const form = document.querySelector('.login-form');
    if (!form) return;

    form.addEventListener('submit', () => {
        const redirect = sessionStorage.getItem('solicitudRedirect');
        if (redirect) {
            // Guardamos en localStorage para leerlo después del login
            localStorage.setItem('redirectAfterLogin', redirect);
            sessionStorage.removeItem('solicitudRedirect');
        }
    });

    // Si ya se ha logueado y Laravel redirige al welcome, reenviamos a destino
    const destino = localStorage.getItem('redirectAfterLogin');
    if (destino && window.location.pathname === '/') {
        localStorage.removeItem('redirectAfterLogin');
        window.location.href = destino;
    }
});
</script>
</body>
</html>