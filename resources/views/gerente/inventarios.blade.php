@extends('layouts.slidebarGerente')

@section('title', 'Gerente | Inventarios')

@section('content')

<!-- HEADER -->
<div class="INVheader">
    <h1>INVENTARIO</h1>
</div>

<!-- ====== CONTENEDOR PRINCIPAL ====== -->
<div class="content" id="cardsContainer">
    <!-- === CARD: FILTROS === -->
    <a href="{{ route('gerente.inventario.ver', 1) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/filtros.png') }}" alt="Filtros">
        </div>
        <div class="inv-divider" style="opacity:.12"></div>
        <div class="inv-text">Filtros</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: AMORTIGUADORES === -->
    <a href="{{ route('gerente.inventario.ver', 2) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/amortiguadores.png') }}" alt="Pares Amortiguadores">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Amortiguadores</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: MUÑONES === -->
    <a href="{{ route('gerente.inventario.ver', 3) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/munones.png') }}" alt="Muñones">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Muñones</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: BUJÍAS === -->
    <a href="{{ route('gerente.inventario.ver', 4) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/bujias.png') }}" alt="Bujías">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Bujías</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: FRENOS === -->
    <a href="{{ route('gerente.inventario.ver', 5) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/frenos.png') }}" alt="Frenos">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Frenos</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: CHICOTILLOS === -->
    <a href="{{ route('gerente.inventario.ver', 6) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/chicotillo.png') }}" alt="Chicotillo">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Chicotillos</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: KIT EMBRAGUE === -->
    <a href="{{ route('gerente.inventario.ver', 7) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/kitenbrague.png') }}" alt="Kit Embrague">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Kit Embrague</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: LÍQUIDOS === -->
    <a href="{{ route('gerente.inventario.ver', 8) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/litros_liquido.png') }}" alt="Litros Líquidos">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Litros Líquidos</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: ACEITE === -->
    <a href="{{ route('gerente.inventario.ver', 9) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/aceite.png') }}" alt="Aceite">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Galones</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: TARROS === -->
    <a href="{{ route('gerente.inventario.ver', 10) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/tarros.png') }}" alt="Grasas">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Grasas</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

    <!-- === CARD: GENERALES === -->
    <a href="{{ route('gerente.inventario.ver', 11) }}" class="inv-card">
        <div class="inv-icon">
            <img src="{{ asset('assets/img/inventarios/juegos.png') }}" alt="Generales">
        </div>
        <div class="inv-divider"></div>
        <div class="inv-text">Generales</div>
        <div class="inv-sub">Ver Todo</div>
    </a>

</div>

@endsection