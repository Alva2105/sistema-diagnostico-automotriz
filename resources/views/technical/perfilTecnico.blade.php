@extends('layouts.slidebarTecnico')

@section('title', 'Técnico | Mi Perfil')

@section('content')

<section class="profile">
    <div class="profile-layout">
        <!-- Columna Izquierda -->
        <div class="profile-left">
            <form id="avatar-form" action="{{ route('perfil.actualizarImagen') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="avatar-wrapper">
                    <img id="avatar-img" src="{{ $usuario->img_usu ? asset('storage/' . $usuario->img_usu) : asset('assets/img/icons/default-user.png') }}" alt="Avatar del usuario">
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
                        <div class="value">{{ $usuario->nom_usu ?? 'Sin nombre' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">person</span>
                            <div class="label">Apellido Paterno</div>
                        </div>
                        <div class="value">{{ $usuario->app_usu ?? 'No especificado' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">person</span>
                            <div class="label">Apellido Materno</div>
                        </div>
                        <div class="value">{{ $usuario->ama_usu ?? 'No especificado' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">email</span>
                            <div class="label">Correo Electrónico</div>
                        </div>
                        <div class="value">{{ $usuario->email_usu ?? 'Sin correo' }}</div>
                    </div>
                </div>
                <div class="col">
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">date_range</span>
                            <div class="label">Fecha de Nacimiento</div>                                        
                        </div>
                        <div class="value">{{ \Carbon\Carbon::parse($usuario->registro->fna_reg ?? '')->format('d/m/Y') }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">contact_phone</span>
                            <div class="label">Teléfono</div>
                        </div>
                        <div class="value">{{ $usuario->cliente->tel_cli ?? 'No registrado' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">id_card</span>
                            <div class="label">C.I.</div>
                        </div>
                        <div class="value">{{ $usuario->registro->cie_reg ?? 'No registrado' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-header">
                            <span class="infoper material-symbols-outlined">home</span>
                            <div class="label">Dirección</div>
                        </div>  
                        <div class="value">{{ $usuario->cliente->dir_cli ?? 'No registrada' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
const input = document.getElementById('avatar-input');
const img = document.getElementById('avatar-img');
const form = document.getElementById('avatar-form');
input.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    // Vista previa instantánea
    const reader = new FileReader();
    reader.onload = e => {
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
    // Espera un instante antes de enviar (permite que el FileReader termine)
    setTimeout(() => {
        form.submit();
    }, 500);
});
</script>

@endsection