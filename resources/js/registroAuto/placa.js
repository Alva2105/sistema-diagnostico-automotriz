document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('placa');
    input.addEventListener('input', () => {
        let valor = input.value.toUpperCase();   // convertimos a mayúsculas
        // Limpiar caracteres no permitidos
        valor = valor.replace(/[^A-Z0-9]/g, '');
        // Limitar longitud a 7
        if (valor.length > 7) {
            valor = valor.slice(0, 7);
        }
        // Separar lógica de los 4 números + 3 letras
        let nuevaPlaca = '';
        for (let i = 0; i < valor.length; i++) {
            if (i < 4) {
                // Solo números en los primeros 4 espacios
                if (/[0-9]/.test(valor[i])) {
                    nuevaPlaca += valor[i];
                }
            } else {
                // Solo letras en los últimos 3 espacios
                if (/[A-Z]/.test(valor[i])) {
                    nuevaPlaca += valor[i];
                }
            }
        }
        input.value = nuevaPlaca;
    });
});