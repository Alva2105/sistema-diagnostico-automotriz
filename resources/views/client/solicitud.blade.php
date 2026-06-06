<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JHIRE MOTORS | Solicitud de Servicio</title>

    <!-- Ícono -->
    <link rel="icon" type="image/png" href="{{ asset('favicons/jhire.ico') }}">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Sansation:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/solicitud.css'])
</head>

<body class="solicitud-body">
    <!-- Header principal -->
    <x-header />

    <main class="solicitud-contenedor">
        <div class="solicitud-wrapper">
            <div class="solicitud-card">
                
                {{-- 🧠 Encabezado --}}
                <div class="solicitud-encabezado">
                    <h1 class="titulo-principal">Solicitud de Servicio</h1>
                    <p class="subtitulo">
                        Completa el siguiente formulario para solicitar tu mantenimiento con <b>JHIRE MOTORS</b>.
                    </p>
                </div>
            
                {{-- Mensajes de éxito --}}
                @if(session('success'))
                    <div class="alert-exito">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('solicitud.enviar') }}" class="formulario-solicitud">
                    @csrf
                
                    <div class="form-grid">
                    
                        {{-- 🔹 BLOQUE 1: Nombre, Correo, Teléfono --}}
                        <div class="grid-1 full-row">
                            <div>
                                <label class="label-jhire">Nombre completo</label>
                                <input type="text" class="input-jhire readonly" readonly
                                    value="{{ $usuario->nom_usu ?? '' }} {{ $usuario->app_usu ?? '' }} {{ $usuario->ama_usu ?? '' }}">
                            </div>
                        
                            <div>
                                <label class="label-jhire">Correo electrónico</label>
                                <input type="email" class="input-jhire readonly" readonly
                                    value="{{ $usuario->email_usu ?? '' }}">
                            </div>
                        
                            <div>
                                <label class="label-jhire">Teléfono / WhatsApp</label>
                                <input type="tel" class="input-jhire readonly" readonly
                                    value="{{ $cliente->tel_cli ?? '' }}">
                            </div>
                        </div>
                    
                        {{-- 🔹 BLOQUE 2: Dirección (fila completa) --}}
                        <div class="full-row">
                            <label class="label-jhire">Dirección</label>
                            <input type="text" class="input-jhire readonly" readonly
                                value="{{ $cliente->dir_cli ?? '' }}">
                        </div>
                        {{-- 🔹 BLOQUE 3 NUEVO: Tipo de mantenimiento + Servicio requerido --}}
                        <div class="grid-2 full-row">
                            <div>
                                <label class="label-jhire">Tipo de mantenimiento</label>
                                <input type="text" id="tipo_mant_display" class="input-jhire readonly" readonly>
                                <input type="hidden" name="tipo_mantenimiento" id="tipo_mant_hidden">
                            </div>
                            <div>
                                <label class="label-jhire">Servicio requerido</label>
                                <div id="servicioContainer" class="servicio-container">
                                    <div class="servicio-summary" id="servicioSummary">
                                        Selecciona un servicio
                                    </div>
                                    <ul class="servicio-list" id="servicioList"></ul>
                                </div>
                                <!-- Campo oculto para enviar al backend -->
                                <input type="hidden" id="servicio_hidden" name="servicio_requerido">
                            </div>
                        
                        </div>
                    
                        {{-- 🔹 BLOQUE 3: Vehículo (selección), Marca, Modelo , Año --}}
                        <div class="grid-3-center full-row">
                        
                            {{-- SI EL CLIENTE TIENE VEHÍCULOS --}}
                            @if($vehiculos->count() > 0)
                        
                                {{-- SELECTOR DE VEHÍCULO (Celda 1 del grid) --}}
                                <div>
                                    <label class="label-jhire">Seleccionar vehículo</label>
                                
                                    <div id="vehiculoContainer" class="vehiculo-container">
                                    
                                        <div class="vehiculo-summary" id="vehiculoSummary">
                                            Selecciona un vehículo
                                        </div>
                                    
                                        <ul class="vehiculo-list" id="vehiculoList">
                                            @foreach($vehiculos as $vehiculo)
                                            <li 
                                                class="vehiculo-item"
                                                data-id="{{ $vehiculo->cod_vehiculos }}"
                                                data-marca="{{ $vehiculo->mar_veh }}"
                                                data-modelo="{{ $vehiculo->mod_veh }}"
                                                data-anio="{{ $vehiculo->ani_veh }}"
                                                data-placa="{{ $vehiculo->cod_vehiculos }}"
                                            >
                                                <span class="material-symbols-rounded vehiculo-icon">
                                                    directions_car
                                                </span>
                                                {{ $vehiculo->cod_vehiculos }}
                                            </li>
                                            @endforeach
                                        </ul>
                                    
                                    </div>
                                
                                    <input type="hidden" name="vehiculo_id" id="vehiculo_hidden">
                                </div>
                            
                                {{-- CAMPOS AUTOLLENABLES (Celda 2, 3 y 4 del grid) --}}
                                <div>
                                    <label class="label-jhire">Marca</label>
                                    <input type="text" id="marca" class="input-jhire" readonly>
                                </div>
                            
                                <div>
                                    <label class="label-jhire">Modelo</label>
                                    <input type="text" id="modelo" class="input-jhire" readonly>
                                </div>
                            
                                <div>
                                    <label class="label-jhire">Año de fabricación</label>
                                    <input type="number" id="anio" class="input-jhire" readonly>
                                </div>
                            
                            @else
                            
                                {{-- SI NO HAY VEHÍCULOS → CAMPOS EDITABLES --}}
                                <div>
                                    <label class="label-jhire">Marca</label>
                                    <input type="text" name="marca" class="input-jhire" placeholder="Toyota...">
                                </div>
                            
                                <div>
                                    <label class="label-jhire">Modelo</label>
                                    <input type="text" name="modelo" class="input-jhire" placeholder="Hilux...">
                                </div>
                            
                                <div>
                                    <label class="label-jhire">Año de fabricación</label>
                                    <input type="number" name="anio" class="input-jhire" placeholder="2020">
                                </div>
                            
                            @endif
                            
                        </div>
                    
                        {{-- 🔹 BLOQUE 4: Descripción (fila completa) --}}
                        <div class="full-row">
                            <label class="label-jhire">Descripción del problema</label>
                            <textarea name="descripcion" rows="4" class="input-jhire textarea" placeholder="Describe brevemente el problema..."></textarea>
                        </div>
                    
                        {{-- 🔹 BLOQUE 5: Fechas (centrado) --}}
                        <div class="grid-4-center full-row">
                            <div>
                                <label class="label-jhire">Fecha de solicitud</label>
                                <input type="text" class="input-jhire readonly" readonly
                                    value="{{ now()->format('d/m/Y') }}">
                            </div>
                            <div>
                                <label class="label-jhire">Fecha preferida</label>
                                <input type="date" id="fecha_preferida" class="input-jhire" name="fecha_preferida" >
                            </div>
                            <div class="time-wrapper">
                                <label class="label-jhire">Hora preferida</label>
                                <input type="text" id="hora_preferida" class="input-jhire" placeholder="HH:MM AM" readonly autocomplete="off">
                                <div id="timePanel" class="time-panel">
                                    <div class="tp-col">
                                        <ul id="tpHours"></ul>
                                    </div>
                                    <div class="tp-col">
                                        <ul id="tpMinutes"></ul>
                                    </div>
                                    <div class="tp-col">
                                        <ul id="tpPeriod">
                                            <li>AM</li>
                                            <li>PM</li>
                                        </ul>
                                    </div>
                                </div>
                                <input type="hidden" id="hora_real" name="hora_preferida_real">
                            </div>
                        </div>
                        
                        {{-- Botón --}}
                        <div class="boton-centro">
                            <button class="btn-jhire" type="submit">Enviar solicitud</button>
                        </div>
                    
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer principal -->
    <x-footer />
<script>
document.addEventListener('DOMContentLoaded', () => {

    // === SELECTOR DE VEHÍCULO ===
    const vehiculoSummary = document.getElementById('vehiculoSummary');
    const vehiculoList = document.getElementById('vehiculoList');
    const vehiculoHidden = document.getElementById('vehiculo_hidden');
    const marcaInput = document.getElementById('marca');
    const modeloInput = document.getElementById('modelo');
    const anioInput = document.getElementById('anio');

    if (vehiculoSummary && vehiculoList) {
        vehiculoSummary.addEventListener('click', () => {
            vehiculoList.classList.toggle('show');
        });

        document.querySelectorAll('.vehiculo-item').forEach(item => {
            item.addEventListener('click', () => {
                vehiculoSummary.innerHTML = item.dataset.placa;
                if (marcaInput) marcaInput.value = item.dataset.marca;
                if (modeloInput) modeloInput.value = item.dataset.modelo;
                if (anioInput) anioInput.value = item.dataset.anio;
                if (vehiculoHidden) vehiculoHidden.value = item.dataset.id;
                vehiculoList.classList.remove('show');
            });
        });
    }

    // === TIPO DE MANTENIMIENTO Y SERVICIO DESDE LOCALSTORAGE ===
    const tipo = localStorage.getItem("tipo_mantenimiento");
    const servicio = localStorage.getItem("servicio_requerido");

    const tipoDisplay = document.getElementById("tipo_mant_display");
    const tipoHidden = document.getElementById("tipo_mant_hidden");
    const servicioHidden = document.getElementById("servicio_hidden");

    if (tipo && tipoDisplay) tipoDisplay.value = tipo;
    if (tipo && tipoHidden) tipoHidden.value = tipo;
    if (servicio && servicioHidden) servicioHidden.value = servicio;

});
</script>
</body>
</html>
