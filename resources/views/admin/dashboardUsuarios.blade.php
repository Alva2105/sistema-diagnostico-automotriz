    @extends('layouts.dashboard')

    @section('contenido')
    <div class="Dusuarios-content">
        @if(session('success'))
            <script>
                window.onload = () => {
                    mostrarAviso("{{ session('success') }}", "success");
                }
            </script>
        @endif

        @if(session('error'))
            <script>
                window.onload = () => {
                    mostrarAviso("{{ session('error') }}", "error");
                }
            </script>
        @endif

        <div class="header-content">
            <h2>Usuarios - {{ $tipo }}
                <button class="btn-nuevoUsu"
                        onclick="abrirModalUsuario()">
                    +Nuevo usuario
                </button>
                <a href="{{ route('usuarios.exportar.excel') }}"
                    class="btn-exportar excel">
                    Excel
                </a>

                <a href="{{ route('usuarios.exportar.pdf') }}"
                    class="btn-exportar pdf">
                    PDF
                </a>
            </h2>
        </div>

        <div class="search-container">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Buscar...">
                <div class="search-icon" id="searchIcon">
                    <span class="material-symbols-outlined buscador">search</span>
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        {{-- CAMBIO: se elimina columna "Estado" porque est_usu no existe en nueva BD --}}
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- CAMBIO: $registros → $usuarios
                        $registro  → $usuario
                        La variable que manda el controlador ahora se llama $usuarios --}}
                    @foreach ($usuarios as $usuario)
                        {{-- CAMBIO: data-id usa cod_usuarios en vez de cod_reg --}}
                        <tr data-id="{{ $usuario->cod_usuarios }}">

                            <td class="id-usuario">
                                {{-- CAMBIO: antes era $registro->usuario->img_usu (dos saltos)
                                    Ahora img_usu está directo en el modelo usuario --}}
                                @if ($usuario->img_usu)
                                    <img src="{{ asset('storage/' . $usuario->img_usu) }}"
                                        alt="Perfil" class="img-mini">
                                @else
                                    <span class="material-symbols-outlined icono-perfil">account_circle</span>
                                @endif
                                {{ $usuario->cod_usuarios }}
                            </td>

                            {{-- CAMBIO: nom_reg, apa_reg, ama_reg → nom_usu, app_usu, apm_usu --}}
                            <td>{{ $usuario->nom_usu }} {{ $usuario->app_usu }} {{ $usuario->apm_usu }}</td>

                            {{-- CAMBIO: coe_reg → email_usu --}}
                            <td>{{ $usuario->email_usu }}</td>

                            {{-- CAMBIO: antes era $registro->usuario->rol->nom_rol
                                Ahora es $usuario->rol->nom_rol directo --}}
                            <td>{{ $usuario->rol->nom_rol ?? 'Sin rol' }}</td>

                            {{-- CAMBIO: se elimina la columna de estado (est_usu no existe en nueva BD)
                                Si en el futuro necesitas estado, agrega la columna a la migración --}}

                            <td class="acciones">
                                {{-- CAMBIO: antes comparaba $registro->usuario->rol->nom_rol
                                    Ahora compara $usuario->rol->nom_rol directo --}}
                                @if (($usuario->rol->nom_rol ?? '') !== 'SuperAdmin')
                                    <button type="button"
                                            class="btn-editar"
                                            onclick="editarUsuario(
                                                '{{ $usuario->cod_usuarios }}',
                                                '{{ $usuario->nom_usu }}',
                                                '{{ $usuario->app_usu }}',
                                                '{{ $usuario->apm_usu }}',
                                                '{{ $usuario->email_usu }}',
                                                '{{ $usuario->cod_roles_usu }}'
                                            )">
                                        Editar
                                    </button>
                                    <button type="button" class="btn-guardar"
                                            style="display:none;"
                                            onclick="guardarEstado(this)">Guardar</button>
                                    <form method="POST"
                                            action="{{ route('usuarios.eliminar', $usuario->cod_usuarios) }}"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-eliminar">
                                            Eliminar
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn-editar" disabled
                                            style="opacity:0.5; cursor:not-allowed;">Editar</button>
                                    <button type="button" class="btn-eliminar" disabled
                                            style="opacity:0.5; cursor:not-allowed;">Eliminar</button>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- CAMBIO: $registros → $usuarios en toda la paginación --}}
        @if ($usuarios->hasPages())
        <div class="pagination-container">

            @if ($usuarios->onFirstPage())
                <span class="page-btn disabled">&#10094;</span>
            @else
                <a href="{{ $usuarios->previousPageUrl() }}" class="page-btn">&#10094;</a>
            @endif

            @if ($usuarios->currentPage() > 3)
                <a href="{{ $usuarios->url(1) }}" class="page-btn">1</a>
                <span class="page-dots">...</span>
            @endif

            @for ($i = max(1, $usuarios->currentPage() - 2);
                $i <= min($usuarios->lastPage(), $usuarios->currentPage() + 2);
                $i++)
                @if ($i == $usuarios->currentPage())
                    <span class="page-btn active">{{ $i }}</span>
                @else
                    <a href="{{ $usuarios->url($i) }}" class="page-btn">{{ $i }}</a>
                @endif
            @endfor

            @if ($usuarios->currentPage() < $usuarios->lastPage() - 2)
                <span class="page-dots">...</span>
                <a href="{{ $usuarios->url($usuarios->lastPage()) }}" class="page-btn">
                    {{ $usuarios->lastPage() }}
                </a>
            @endif

            @if ($usuarios->hasMorePages())
                <a href="{{ $usuarios->nextPageUrl() }}" class="page-btn">&#10095;</a>
            @else
                <span class="page-btn disabled">&#10095;</span>
            @endif

        </div>
        @endif
    </div>

    {{-- MODAL NUEVO USUARIO --}}
    <div id="modalNuevoUsuario" class="modal-usuario">
        <div class="modal-contenido">

            <div class="modal-header">
                <h2>Registrar Nuevo Usuario</h2>
                <span class="cerrar-modal" onclick="cerrarModalUsuario()">&times;</span>
            </div>

            <form id="formUsuario"
                action="{{ route('usuarios.guardar') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                <input type="hidden" id="methodField" name="_method" value="POST">

                <div class="form-grid">

                    <div class="form-group">
                        <label>Nombres</label>
                        <input type="text" name="nom_usu" required>
                    </div>

                    <div class="form-group">
                        <label>Apellido Paterno</label>
                        <input type="text" name="app_usu" required>
                    </div>

                    <div class="form-group">
                        <label>Apellido Materno</label>
                        <input type="text" name="apm_usu">
                    </div>

                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" name="email_usu" required>
                    </div>

                    <div class="form-group">
                        <label>Contraseña</label>
                        <input type="password" name="pas_usu" required>
                    </div>

                    <div class="form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" name="pas_usu_confirmation" required>
                    </div>

                    <div class="form-group">
                        <label>Rol</label>

                        <select name="cod_roles_usu" required>
                            <option value="">Seleccione...</option>

                            @foreach(\App\Models\Rol::all() as $rol)
                                <option value="{{ $rol->cod_roles }}">
                                    {{ $rol->nom_rol }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group">
                        <label>Imagen de Perfil*</label>
                        <input type="file" name="img_usu" accept="image/*">
                    </div>

                </div>

                <div class="modal-botones">
                    <button type="submit" class="btn-guardar-modal">
                        Guardar Usuario
                    </button>

                    <button type="button"
                            class="btn-cancelar-modal"
                            onclick="cerrarModalUsuario()">
                        Cancelar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <style>

        /* =========================================================
        MODAL NUEVO USUARIO
        ========================================================= */

    .modal-usuario {
        display: none;
        position: fixed;
        z-index: 99999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }

    .modal-contenido {
        width: 700px;
        max-width: 95%;
        background: #1f1f1f;
        border-radius: 14px;
        padding: 30px;
        border: 2px solid #ff6600;
        box-shadow: 0 0 25px rgba(255,102,0,0.4);
        animation: aparecerModal 0.25s ease;
    }

    @keyframes aparecerModal {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .modal-header h2 {
        color: #ff6600;
        margin: 0;
    }

    .cerrar-modal {
        color: white;
        font-size: 30px;
        cursor: pointer;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 6px;
        color: #fff;
        font-size: 14px;
    }

    .form-group input,
    .form-group select {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #555;
        background: #2d2d2d;
        color: white;
        outline: none;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #ff6600;
    }

    .modal-botones {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 25px;
    }

    .btn-guardar-modal {
        background: #ff6600;
        color: black;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }

    .btn-cancelar-modal {
        background: #444;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        cursor: pointer;
    }

    .id-usuario {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .img-mini {
        width: 40px;
        height: auto;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #ff7b00;
    }
    .icono-perfil {
        font-size: 40px;
        color: #ff7b00;
        vertical-align: middle;
    }
    .buscador {
        color: #000000ff;
    }
    .select-estado {
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 13px;
    }

    .btn-exportar{
        padding: 10px 15px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: bold;
        margin-left: 10px;
        transition: 0.2s;
    }

    .btn-exportar.excel{
        background: #1D6F42;
        color: white;
    }

    .btn-exportar.pdf{
        background: #D32F2F;
        color: white;
    }

    .btn-exportar:hover{
        opacity: 0.85;
    }
    </style>

    <script>
    function activarEdicionEstado(boton) {
        const fila = boton.closest("tr");
        fila.querySelector(".btn-editar").style.display = "none";
        fila.querySelector(".btn-guardar").style.display = "inline-block";
    }

    async function guardarEstado(boton) {
        // CAMBIO: est_usu no existe en la nueva BD.
        // Esta función queda como placeholder para futuras acciones de edición
        // (por ejemplo editar nombre, email o rol del usuario).
        mostrarAviso("⚠️ Edición de estado no disponible en esta versión.", "error");

        const fila = boton.closest("tr");
        fila.querySelector(".btn-editar").style.display = "inline-block";
        fila.querySelector(".btn-guardar").style.display = "none";
    }

    function mostrarAviso(mensaje, tipo = "success") {
        const aviso = document.createElement("div");
        aviso.textContent = mensaje;
        aviso.style.position = "fixed";
        aviso.style.bottom = "20px";
        aviso.style.right = "20px";
        aviso.style.padding = "12px 18px";
        aviso.style.borderRadius = "8px";
        aviso.style.fontWeight = "bold";
        aviso.style.color = tipo === "success" ? "#fff" : "#000";
        aviso.style.backgroundColor = tipo === "success" ? "#00c851" : "#ffbb33";
        aviso.style.boxShadow = "0 0 10px rgba(0,0,0,0.3)";
        aviso.style.zIndex = 9999;
        aviso.style.transition = "opacity 0.5s ease";
        document.body.appendChild(aviso);
        setTimeout(() => aviso.style.opacity = "0", 2000);
        setTimeout(() => aviso.remove(), 2500);
    }

    function abrirModalUsuario()
    {
        const form = document.getElementById("formUsuario");

        form.reset();

        form.action = "{{ route('usuarios.guardar') }}";

        document.getElementById("methodField").value = "POST";

        document.querySelector('input[name="pas_usu"]').required = true;
        document.querySelector('input[name="pas_usu_confirmation"]').required = true;

        document.getElementById("modalNuevoUsuario").style.display = "flex";
    }

    function cerrarModalUsuario() {
        document.getElementById("modalNuevoUsuario").style.display = "none";
    }

    function editarUsuario(id, nombre, app, apm, email, rol)
    {
        abrirModalUsuario();

        const form = document.getElementById("formUsuario");

        form.action = `/dashboard/usuarios/${id}/actualizar`;

        document.getElementById("methodField").value = "PUT";

        document.querySelector('input[name="nom_usu"]').value = nombre;
        document.querySelector('input[name="app_usu"]').value = app;
        document.querySelector('input[name="apm_usu"]').value = apm;
        document.querySelector('input[name="email_usu"]').value = email;
        document.querySelector('select[name="cod_roles_usu"]').value = rol;

        // contraseña vacía
        document.querySelector('input[name="pas_usu"]').required = false;
        document.querySelector('input[name="pas_usu_confirmation"]').required = false;

        document.querySelector('input[name="pas_usu"]').value = '';
        document.querySelector('input[name="pas_usu_confirmation"]').value = '';
    }

    window.onclick = function(event) {
        const modal = document.getElementById("modalNuevoUsuario");

        if (event.target === modal) {
            cerrarModalUsuario();
        }
    }
    </script>
    
    <script>

    function abrirModalVehiculo()
    {
        const form = document.getElementById("formVehiculo");

        form.reset();

        form.action = "{{ route('vehiculos.guardar') }}";

        document.getElementById("methodVehiculo").value = "POST";

        document.querySelector('select[name="cod_clientes_veh"]').disabled = false;

        document.getElementById("tituloModalVehiculo").innerText =
            "Registrar Vehículo";

        document.getElementById("modalVehiculo").style.display = "flex";
    }

    function cerrarModalVehiculo()
    {
        document.getElementById("modalVehiculo").style.display = "none";
    }

    function editarVehiculo(
        codVehiculo,
        codCliente,
        placa,
        marca,
        modelo,
        anio,
        color,
        tipo
    )   
    {
        abrirModalVehiculo();

        const form = document.getElementById("formVehiculo");

        form.action =
            `/dashboard/vehiculos/${codVehiculo}/${codCliente}/actualizar`;

        document.getElementById("methodVehiculo").value = "PUT";

        form.pla_veh.value = placa;
        form.mar_veh.value = marca;
        form.mod_veh.value = modelo;
        form.ani_veh.value = anio;
        form.col_veh.value = color;
        form.tip_veh.value = tipo;

        form.cod_clientes_veh.value = codCliente;

        // no permitir cambiar cliente
        document.querySelector('select[name="cod_clientes_veh"]').disabled = true;

        document.getElementById("tituloModalVehiculo").innerText =
            "Editar Vehículo";
    }

    window.addEventListener("click", function(e)
    {
        const modal = document.getElementById("modalVehiculo");

        if (e.target === modal)
        {
            cerrarModalVehiculo();
        }
    });

    </script>

    @push('scripts')
        @vite('resources/js/dashboard/buscador.js')
    @endpush

    @endsection