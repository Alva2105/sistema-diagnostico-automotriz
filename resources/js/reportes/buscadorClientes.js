document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('searchTInput');
    if (!input) return;

    input.addEventListener('input', function () {
        const q = input.value.trim().toLowerCase();
        const rows = document.querySelectorAll('.tabla-solicitud tbody tr');

        rows.forEach(row => {
            // si es la fila "no encontrado" la dejamos
            if (row.querySelectorAll('td').length === 1) return;

            const text = Array.from(row.querySelectorAll('td'))
                .map(td => td.textContent.trim().toLowerCase())
                .join(' ');

            const show = q === '' ? true : text.includes(q);
            row.style.display = show ? '' : 'none';
        });
    });

    // opcional: buscar al presionar Enter (mantener cursor)
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            input.value = '';
            input.dispatchEvent(new Event('input'));
        }
    });
});