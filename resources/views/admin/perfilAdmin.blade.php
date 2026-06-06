@extends('layouts.dashboard')

@section('contenido')

@php use Illuminate\Support\Facades\Storage; @endphp

<main class="contentPerfilAdmin">
    <section class="profile">
        <div class="profileAdmin-layout">

            <!-- Columna Izquierda -->
            <div class="profile-left">
                <form id="avatar-form" action="{{ route('perfil.actualizarImagen') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-wrapper">
                        <img id="avatar-img"
                             src="{{ $usuario->img_usu
                                     ? Storage::url($usuario->img_usu)
                                     : asset('assets/img/icons/default-user.png') }}"
                             alt="Avatar del usuario">

                        <label for="avatar-input" class="edit-icon">
                            <img src="{{ asset('assets/img/icons/edit-pencil.svg') }}" class="edit-svg">
                        </label>

                        <input type="file" id="avatar-input" name="avatar" accept="image/*" hidden>
                    </div>
                </form>
            </div>

            <!-- Columna Derecha -->
            <div class="profile-right">
                <div class="details">
                    <div class="col">

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">person</span>
                                <div class="label">Nombre</div>
                            </div>
                            {{-- CAMBIO: antes era $usuario->registro->nom_reg
                                 Ahora el nombre está directo en el modelo usuario --}}
                            <div class="value">{{ $usuario->nom_usu ?? 'Sin nombre' }}</div>
                        </div>

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">person</span>
                                <div class="label">Apellido Paterno</div>
                            </div>
                            {{-- CAMBIO: apa_reg → app_usu --}}
                            <div class="value">{{ $usuario->app_usu ?? 'No especificado' }}</div>
                        </div>

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">person</span>
                                <div class="label">Apellido Materno</div>
                            </div>
                            {{-- CAMBIO: ama_reg → apm_usu (antes estaba como ama_usu, error tipográfico) --}}
                            <div class="value">{{ $usuario->apm_usu ?? 'No especificado' }}</div>
                        </div>

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">email</span>
                                <div class="label">Correo Electrónico</div>
                            </div>
                            {{-- CAMBIO: antes era coe_reg desde registro. Ahora es email_usu directo --}}
                            <div class="value">{{ $usuario->email_usu ?? 'Sin correo' }}</div>
                        </div>

                    </div>

                    <div class="col">

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">badge</span>
                                <div class="label">Rol</div>
                            </div>
                            {{-- CAMBIO: se elimina CI y fecha de nacimiento (no existen en nueva BD para usuarios)
                                 Se muestra el rol en su lugar, que sí existe --}}
                            <div class="value">{{ $usuario->rol->nom_rol ?? 'Sin rol' }}</div>
                        </div>

                        <div class="field">
                            <div class="field-header">
                                <span class="infoper material-symbols-outlined">tag</span>
                                <div class="label">ID de Usuario</div>
                            </div>
                            {{-- CAMBIO: cod_sup de superadmin ya no existe como tabla separada
                                 Mostramos cod_usuarios directamente --}}
                            <div class="value">{{ $usuario->cod_usuarios }}</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
</main>

<script>
const input = document.getElementById('avatar-input');
const img   = document.getElementById('avatar-img');
const form  = document.getElementById('avatar-form');

input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; };
    reader.readAsDataURL(file);
    setTimeout(() => { form.submit(); }, 500);
});
</script>

@push('scripts')
    @vite('resources/js/dashboard/buscador.js')
@endpush

@endsection