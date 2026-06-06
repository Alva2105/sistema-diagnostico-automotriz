<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHIRE MOTORS | Registro</title>
    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="registro-body">
    <div class="fondo-green"></div>
    
    <a href="{{ route('welcome') }}" class="back-link">
        <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle;">arrow_back</span>
        Volver
    </a>
    
    <div class="contenedor">
        <h1 class="title">REGISTRO</h1>
        
        <form id="formRegistro" method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="fila">
                <div class="input-container">
                    <input type="text" name="name" id="nombre" placeholder="Nombres" maxlength="20" tabindex="1" required value="{{ old('name') }}">
                    <small id="NameHelp"></small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="NameError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="NameCheck" class="icono icono-check">
                    <div id="mensajeNombre" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="NameRueda" class="icono-rueda">
                </div>
                <div class="input-container">
                    <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" required tabindex="2" required value="{{ old('email') }}">
                    <small id="CorreoHelp">Ingrese su correo electrónico de preferencia</small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="CorreoError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="CorreoCheck" class="icono icono-check">
                    <div id="mensajeCorreo" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="CorreoRueda" class="icono-rueda">
                </div>
            </div>

            <div class="fila">
                <div class="input-container">
                    <input type="text" name="appaterno" id="appaterno" placeholder="Apellido Paterno" maxlength="20" tabindex="3" required value="{{ old('appaterno') }}">
                    <small id="ApPaternoHelp"></small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="ApPaternoError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="ApPaternoCheck" class="icono icono-check">
                    <div id="mensajeApPaterno" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="ApPaternoRueda" class="icono-rueda">
                </div>
                <div class="input-container">
                    <input type="password" name="contrasena" id="contrasena" placeholder="Contraseña" required minlength="8" maxlength="10" tabindex="4" required value="{{ old('password') }}">
                    <button type="button" id="togglePassword" class="ver_contra">
                        <img src="{{ asset('assets/img/icons/ojo_cerrado.png') }}" alt="Ver contraseña" id="ojito1">
                    </button>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="ContraError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="ContraCheck" class="icono icono-check">
                    <small id="ContrasenaHelp">Ingrese su contraseña de 8 a 10 caracteres</small>
                    <div id="mensajeContrasena" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="ContrasenaRueda" class="icono-rueda">
                </div>
            </div>

            <div class="fila">
                <div class="input-container">
                    <input type="text" name="apmaterno" id="apmaterno" placeholder="Apellido Materno" maxlength="20" tabindex="5" required value="{{ old('apmaterno') }}">
                    <small id="ApMaternoHelp"></small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="ApMaternoError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="ApMaternoCheck" class="icono icono-check">
                    <div id="mensajeApMaterno" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="ApMaternoRueda" class="icono-rueda">
                </div>
                <div class="input-container">
                    <input type="password" name="confirmarContrasena" id="confirmarContrasena" placeholder="Confirmar Contraseña" required minlength="8" maxlength="10" tabindex="6" required value="{{ old('confirmar') }}">
                    <button type="button" id="toggleConfirmar" class="ver_contra">
                        <img src="{{ asset('assets/img/icons/ojo_cerrado.png') }}" alt="Ver confirmación" id="ojitoConfirmar">
                    </button>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="ConfirmError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="ConfirmCheck" class="icono icono-check">
                    <small id="ConfirmHelp">Revise que las contraseñas coincidan</small>
                    <div id="mensajeConfirmar" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="ConfirmRueda" class="icono-rueda">
                </div>
            </div>

            <div class="fila">
                <div class="input-container">
                    <input type="tel" name="CI" id="CI" placeholder="CI" maxlength="8" tabindex="7" required value="{{ old('CI') }}">
                    <small id="CIHelp"></small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="CIError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="CICheck" class="icono icono-check">
                    <div id="mensajeCI" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="CIRueda" class="icono-rueda">
                </div>
                <div class="input-container">
                    <input class="telefono" type="tel" name="telefono" id="telefono" placeholder="+591  XXX - XX - XXX" required tabindex="8" required value="{{ old('telefono') }}">
                    <div class="input-group">
                        <img src="{{ asset('assets/img/icons/bandera_bolivia.png') }}" alt="BOL" id="banderaBOL" class="bandera-icono">
                    </div>
                    <small id="TelefonoHelp">Ingrese su número de celular</small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="TelefonoError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="TelefonoCheck" class="icono icono-check">
                    <div id="mensajeTelefono" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="TelefonoRueda" class="icono-rueda">
                </div>
            </div>

            <div class="fila">
                <div class="input-container">
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección" maxlength="100" minlength="5" tabindex="10" required value="{{ old('direccion') }}">
                    <small id="DireccionHelp">Ingrese la dirección de su domicilio</small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="DireccionError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="DireccionCheck" class="icono icono-check">
                    <div id="mensajeDireccion" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="DireccionRueda" class="icono-rueda">
                </div>
                <div class="input-container" style="position: relative;">
                    <input type="date" name="fechaNacimiento" id="fechaNacimiento" required tabindex="11" value="{{ old('fechaNacimiento') }}">
                    <small id="FechaHelp">Ingrese su Fecha de Nacimiento</small>
                    <img src="{{ asset('assets/img/icons/error.png') }}" alt="error" id="FechaError" class="icono icono-error">
                    <img src="{{ asset('assets/img/icons/check.png') }}" alt="check" id="FechaCheck" class="icono icono-check">
                    <div id="mensajeFecha" class="error-mensaje"></div>
                    <img src="{{ asset('assets/img/icons/ruedaError.png') }}" alt="rueda" id="FechaRueda" class="icono-rueda">
                </div>
            </div>

            <button type="submit" class="btn-reg" id="btnRegistrar" disabled>Registrar</button>
        </form>
    </div>
</body>
</html>