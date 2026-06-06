@extends('layouts.slidebarGerente')

@section('content')

@push('style')
<style>
    .content-area {
        background-color: #2e2e2e !important;
    }
</style>
@endpush

@php use Illuminate\Support\Facades\Storage; @endphp

<main class="content-Cliente">
    <section class="profile">
        <div class="profile-layout">
            <!-- Columna Izquierda -->
            <div class="profile-left">
                <form id="avatar-form" action="{{ route('perfil.actualizarImagen') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="avatar-wrapper">
                    
                        @php
                            // Ruta bruta desde BD
                            $ruta = $usuario->img_usu;
                    
                            // Validación para asegurar que siempre tenga prefijo 'usuarios/'
                            if ($ruta && !str_starts_with($ruta, 'usuarios/')) {
                                $ruta = 'usuarios/' . $ruta;
                            }
                        @endphp
                    
                        <img id="avatar-img"
                            src="{{ $usuario->img_usu ? Storage::url($usuario->img_usu) : asset('assets/img/icons/default-user.png') }}"
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
                            <div class="label">Nombre</div>
                            <div class="value">{{ $usuario->nom_usu }}</div>
                        </div>
                    
                        <div class="field">
                            <div class="label">Apellido Paterno</div>
                            <div class="value">{{ $usuario->app_usu }}</div>
                        </div>
                    
                        <div class="field">
                            <div class="label">Apellido Materno</div>
                            <div class="value">{{ $usuario->ama_usu }}</div>
                        </div>
                    
                        <div class="field">
                            <div class="label">Correo Electrónico</div>
                            <div class="value">{{ $usuario->email_usu }}</div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="field">
                            <div class="label">C.I.</div>
                            <div class="value">{{ $usuario->registro->cie_reg }}</div>
                        </div>
                    
                        <div class="field">
                            <div class="label">Fecha de Nacimiento</div>
                            <div class="value">
                                {{ $usuario->registro->fna_reg ? \Carbon\Carbon::parse($usuario->registro->fna_reg)->format('d/m/Y') : 'No registrado' }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>
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