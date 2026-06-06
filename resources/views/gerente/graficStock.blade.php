<div id="modalGrafico" class="modal-grafico" style="display:none;">
    <div class="modal-content">
        <h2 id="tituloGrafico"></h2>

        {{-- CANVAS DEL GRÁFICO --}}
        <canvas id="graficoStock" width="900" height="600"></canvas>

        <p class="detalle-grafico">
            <strong>Actual:</strong> <span id="stockActual"></span><br>
            <strong>Punto de Reorden:</strong> <span id="stockReorden"></span><br>
            <strong>Stock de Seguridad:</strong> <span id="stockSeguridad"></span>
        </p>

        <button onclick="cerrarGrafico()" class="btnCerrar">Cerrar</button>
    </div>
</div>

@push('scripts')
    @vite('resources/js/inventario/grafico.js')
@endpush

@push('style')
    @vite('resources/css/grafico.css')
@endpush