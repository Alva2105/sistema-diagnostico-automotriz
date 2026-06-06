<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JHIRE MOTORS | Registro de Vehículo</title>

    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">
    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="rv-body">

    <!-- Header -->
    <x-header />

    <!-- Fondo con blur -->
    <div class="rv-overlay"></div>

    <!-- Contenedor principal -->
    <div class="rv-contenedor">

        @if (session('success'))
            <div class="alerta-exito">
                {{ session('success') }}
            </div>
        @endif

        <div id="alerta-vehiculo" class="alerta-mensaje" style="display:none;"></div>

        <!-- Formulario -->
        <form id="formVehiculo" class="rv-formulario" action="{{ route('vehiculos.store') }}" method="POST">
            @csrf

            <div class="rv-titulo">REGISTRO DE VEHÍCULO</div>

            <!-- FILA 1 -->
            <div class="rv-fila">           

                <!-- SELECT PERSONALIZADO: TIPO -->
                <div class="rv-grupo">
                    <label class="rv-label">Tipo (Opcional)
                        <span>Ej: Auto, Moto, Camioneta…</span>
                    </label>                

                    <div class="rv-select" id="select-tipo" data-type="tipo">
                        <div class="rv-select-trigger">
                            <span class="rv-select-text">Seleccionar Tipo</span>
                            <span class="material-symbols-rounded rv-select-icon">expand_more</span>
                        </div>              

                        <div class="rv-options">                

                            <div class="rv-option" data-value="Auto">
                                <span>Auto</span>
                            </div>              

                            <div class="rv-option" data-value="Camioneta">
                                <span>Camioneta</span>
                            </div>

                            <div class="rv-option" data-value="SUV">
                                <span>SUV</span>
                            </div>

                            <div class="rv-option" data-value="Jeep">
                                <span>Jeep</span>
                            </div>              

                            <div class="rv-option" data-value="Bus">
                                <span>Bus</span>
                            </div>

                            <div class="rv-option" data-value="Minibus">
                                <span>Minibus</span>
                            </div>

                            <div class="rv-option" data-value="Camión">
                                <span>Camión</span>
                            </div>              

                            <div class="rv-option" data-value="Minivan">
                                <span>Minivan</span>
                            </div>

                            <div class="rv-option" data-value="Cuadratac">
                                <span>Cuadratac</span>
                            </div>

                            <!-- ⭐ OPCIÓN PERSONALIZADA -->
                            <div class="rv-option rv-option-tipo-custom" data-value="_custom">                                
                                <span> Escribe el tipo de tu vehículo</span>
                                <span class="material-symbols-rounded rv-option-custom">stylus</span>
                            </div>              

                        </div>
                    </div>              

                    <!-- input oculto real -->
                    <input type="hidden" id="tipo" name="tipo">         

                    <!-- input visible cuando es personalizado -->
                    <input type="text" id="tipo-custom" class="rv-input" placeholder="Ingresa el tipo (ej: Cuatrimoto)" maxlength="12" style="display: none;">
                </div>          

                <!-- SELECT PERSONALIZADO: MARCA -->
                <div class="rv-grupo">
                    <label class="rv-label">Marca (Opcional)
                        <span>Ej: Toyota, Hyundai…</span>
                    </label>                

                    <div class="rv-select" id="select-marca" data-type="marca">
                        <div class="rv-select-trigger">
                            <span class="rv-select-text">Seleccionar Marca</span>
                            <span class="material-symbols-rounded rv-select-icon">expand_more</span>
                        </div>              

                        <div class="rv-options">
                            <div class="rv-option" data-value="Suzuki">
                                <img src="/assets/img/logos/suzuki.png"><span>Suzuki</span>
                            </div>              

                            <div class="rv-option" data-value="Nissan">
                                <img src="/assets/img/logos/nissan.svg"><span>Nissan</span>
                            </div>              

                            <div class="rv-option" data-value="Toyota">
                                <img src="/assets/img/logos/toyota.svg"><span>Toyota</span>
                            </div>              

                            <div class="rv-option" data-value="Changan">
                                <img src="/assets/img/logos/changan.png"><span>Changan</span>
                            </div>              

                            <div class="rv-option" data-value="Hyundai">
                                <img src="/assets/img/logos/hyundai.png"><span>Hyundai</span>
                            </div>              

                            <div class="rv-option" data-value="Chevrolet">
                                <img src="/assets/img/logos/chevrolet.png"><span>Chevrolet</span>
                            </div>

                            <div class="rv-option rv-option-custom" data-value="_custom">
                                <span>Escribe la marca de tu vehículo</span>
                                <span class="material-symbols-rounded rv-option-custom">stylus</span>
                            </div>
                            
                        </div>
                    </div>          

                    <!-- input oculto real -->
                    <input type="hidden" id="marca" name="marca">

                    <!-- Input visible cuando elige marca personalizada -->
                    <input type="text" id="marca-custom" class="rv-input" placeholder="Ingresa la marca (máx 12 letras)" maxlength="12" style="display: none;">
                </div>          
            </div>            

            <!-- FILA 2 -->
            <div class="rv-fila">           

                <div class="rv-grupo">
                    <label class="rv-label">Año de Fabricación (Opcional)
                        <span>Ej: 1980, 2010, 2020…</span>
                    </label>
                    <input type="number" name="anio" class="rv-input" placeholder="Año de Fabricación" min="1960" max="2025">
                </div>          

                <!-- SELECT PERSONALIZADO: MODELO -->
                <div class="rv-grupo">
                    <label class="rv-label">Modelo (Opcional)
                        <span>Ej: Según la marca seleccionada…</span>
                    </label>                
                    <div class="rv-select" id="select-modelo" data-type="modelo">
                        <div class="rv-select-trigger">
                            <span class="rv-select-text">Seleccione una marca primero</span>
                            <span class="material-symbols-rounded rv-select-icon">expand_more</span>
                        </div>              
                        <div class="rv-options"></div>
                    </div>              
                    <input type="hidden" id="modelo" name="modelo">
                    <!-- input para modelo personalizado -->
                    <input  type="text" id="modelo-custom" class="rv-input" placeholder="Ingresa el modelo (A-Z, 0-9 y '-')" maxlength="12" style="display: none;">
                </div>          
            </div>

            <!-- FILA 3 -->
            <div class="rv-fila">

                <div class="rv-grupo">
                    <label class="rv-label">Color (Opcional)
                        <span>Ej: Rojo, Blanco…</span>
                    </label>
                    <input type="text" class="rv-input" name="color" placeholder="Color">
                </div>
                <!-- PLACA -->
                <div class="rv-placa-box">
                    <small class="rv-placa-pais">B O L I V I A</small>
                    <input type="text" id="placa" name="placa" class="rv-input-placa" maxlength="7" autocomplete="off" placeholder="1234ABC" required/>
                    <small class="rv-placa-text">Placa del Automóvil (1234ABC)</small>
                </div>
            </div>

            <button class="rv-btn" type="submit">Registrar</button>
        </form>

    </div>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("formVehiculo");
    const alerta = document.getElementById("alerta-vehiculo");

    form.addEventListener("submit", (e) => {
        // Tomar valores actuales de los campos
        const datos = {
            tipo: document.getElementById("tipo").value || document.getElementById("tipo-custom").value,
            marca: document.getElementById("marca").value || document.getElementById("marca-custom").value,
            modelo: document.getElementById("modelo").value || document.getElementById("modelo-custom").value,
            anio: document.querySelector("input[name='anio']").value,
            color: document.querySelector("input[name='color']").value,
            placa: document.getElementById("placa").value,
        };

        let errores = [];

        // VALIDACIÓN: placa obligatoria
        if (!datos.placa || datos.placa.length < 7) {
            errores.push("⚠ Debe ingresar una placa válida (formato 1234ABC).");
        }

        if (errores.length > 0) {
            // ❌ Hay errores: no mandamos el formulario
            e.preventDefault();
            mostrarMensaje(errores.join("\n"), "error");
        }
    });

    function mostrarMensaje(texto, tipo) {
        alerta.textContent = texto;

        alerta.style.display = "block";
        alerta.className = "alerta-mensaje " + tipo;

        setTimeout(() => {
            alerta.style.opacity = "0";
        }, 2000);

        setTimeout(() => {
            alerta.style.display = "none";
            alerta.style.opacity = "1";
        }, 3000);
    }

});
</script>
    <!-- Footer global -->
    <x-footer />
</body>
</html>