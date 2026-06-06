@extends('layouts.slidebarGerente')

@section('title', 'Gerente | ' . mb_strtoupper($categoria->nom_cat))

@section('content')

<div class="INVheader">
    <h1 class="INVtitle">{{ mb_strtoupper($categoria->nom_cat) }}</h1>
</div>

<div class="tabla-container">

    <div class="headerTabla">
        <a href="{{ route('gerente.inventarios') }}" class="btnVolver">
            <span class="material-symbols-outlined" style="font-size:20px; vertical-align:middle;">arrow_back</span>
            Volver
        </a>
    </div>

    <table class="tabla-inventario">
        <thead>
            <tr>
                <th class="th-inventario">Nro.</th>
                <th class="th-inventario">Nombre</th>
                <th class="th-inventario">Cantidad Disponible</th>
                <th class="th-inventario">Estado</th>
                <th class="th-inventario">Nivel de Stock</th>
                <th class="th-inventario">Precio Unitario</th>
                <th class="th-inventario">Especificaciones</th>
                <th class="th-inventario">Acción</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($repuestos as $i => $r)
            <tr>
                <td class="td-inventario">{{ $repuestos->firstItem() + $i }}</td>

                <td class="td-inventario">{{ $r->nom_rep }}</td>

                {{-- CAMBIO: cant_rep → stock --}}
                <td class="td-inventario">
                    <span class="texto-cantidad">{{ $r->stock }}</span>
                    <input
                        type="number"
                        class="input-cantidad"
                        value="{{ $r->stock }}"
                        min="0"
                        style="display:none;">
                </td>

                {{-- CAMBIO: cant_rep → stock. El estado ya no viene de BD,
                     se calcula comparando stock con los umbrales. --}}
                <td class="td-inventario">
                    @if($r->stock == 0)
                        <span class="estado agotado">AGOTADO</span>
                    @elseif($r->stock <= $r->cse_rep)
                        <span class="estado por-agotarse">POR AGOTARSE</span>
                    @elseif($r->stock <= $r->nre_rep)
                        <span class="estado reorden">NIVEL DE REORDEN</span>
                    @else
                        <span class="estado almacen">EN ALMACÉN</span>
                    @endif
                </td>

                {{-- CAMBIO: cant_rep → stock --}}
                <td class="td-inventario">
                    <button class="btn-nivel"
                            onclick="mostrarGrafico({{ $r->stock }}, {{ $r->nre_rep ?? 0 }}, {{ $r->cse_rep ?? 0 }}, '{{ $r->nom_rep }}')">
                        Ver Nivel de Stock
                    </button>
                </td>

                {{-- CAMBIO: pun_rep no existe → usar pre_rep --}}
                <td class="td-inventario">{{ $r->pre_rep }} Bs</td>

                {{-- CAMBIO:
                     - cod_rep    → cod_repuestos
                     - cat_rep    → categoria (relación) → nom_cat
                     - mar_rep    → eliminado (no existe en nueva BD), reemplazado por pre_rep
                     - mod_rep    → eliminado (no existe en nueva BD), reemplazado por stock
                     - des_rep    → eliminado (no existe en nueva BD)
                     - img_rep    → eliminado (no existe en nueva BD), placeholder fijo
                --}}
                <td class="td-inventario">
                    <button class="detalle-link"
                        data-id="{{ $r->cod_repuestos }}"
                        data-nombre="{{ $r->nom_rep }}"
                        data-categoria="{{ $r->categoria->nom_cat ?? '—' }}"
                        data-cmax="{{ $r->cma_rep ?? 0 }}"
                        data-cmin="{{ $r->cmi_rep ?? 0 }}"
                        data-precio="{{ $r->pre_rep }}"
                        data-stock="{{ $r->stock }}"
                        data-descripcion="Sin descripción"
                        data-foto="{{ asset('img/placeholders/item.png') }}"
                        onclick="mostrarDetalles(this)">
                        Mostrar Detalles
                    </button>
                </td>

                {{-- CAMBIO:
                     - est_rep ya no existe en BD, se calcula dinámicamente
                     - cod_rep → cod_repuestos
                     El botón "Ajustar Stock" se muestra cuando el stock
                     está en nivel de reorden o por agotarse.
                --}}
                <td class="td-inventario">
                    @if($r->stock <= ($r->nre_rep ?? 0))
                        <button class="btn-editar"
                                data-id="{{ $r->cod_repuestos }}"
                                data-nre="{{ $r->nre_rep ?? 0 }}"
                                data-cse="{{ $r->cse_rep ?? 0 }}"
                                onclick="activarEdicion(this)">
                            Ajustar Stock
                        </button>
                    @else
                        <button class="btn-editar disabled-btn" disabled
                                title="Solo ajustable cuando el repuesto está en NIVEL DE REORDEN">
                            Ajustar Stock
                        </button>
                    @endif
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    @include('gerente.graficStock')
    @include('gerente.detallesRepuestos')

    @if ($repuestos->hasPages())
    <div class="pagination-container">

        @if ($repuestos->onFirstPage())
            <span class="page-btn disabled">&#10094;</span>
        @else
            <a href="{{ $repuestos->previousPageUrl() }}" class="page-btn">&#10094;</a>
        @endif

        @if ($repuestos->currentPage() > 3)
            <a href="{{ $repuestos->url(1) }}" class="page-btn">1</a>
            <span class="page-dots">...</span>
        @endif

        @for ($i = max(1, $repuestos->currentPage() - 2);
                $i <= min($repuestos->lastPage(), $repuestos->currentPage() + 2);
                $i++
        )
            @if ($i == $repuestos->currentPage())
                <span class="page-btn active">{{ $i }}</span>
            @else
                <a href="{{ $repuestos->url($i) }}" class="page-btn">{{ $i }}</a>
            @endif
        @endfor

        @if ($repuestos->currentPage() < $repuestos->lastPage() - 2)
            <span class="page-dots">...</span>
            <a href="{{ $repuestos->url($repuestos->lastPage()) }}" class="page-btn">
                {{ $repuestos->lastPage() }}
            </a>
        @endif

        @if ($repuestos->hasMorePages())
            <a href="{{ $repuestos->nextPageUrl() }}" class="page-btn">&#10095;</a>
        @else
            <span class="page-btn disabled">&#10095;</span>
        @endif

    </div>
    @endif

</div>

<div id="inv-toast" class="inv-toast">Cantidad en Stock Ajustada</div>

@push('style')
    @vite('resources/css/inventarioTabla.css')
@endpush

@push('scripts')
    @vite('resources/js/inventario/cantRepuesto.js')
    @vite('resources/js/inventario/abrirDetalles.js')
@endpush

@endsection