document.getElementById('addToCartBtn').addEventListener('click', function() {
    const cantidad = document.getElementById('cantidad').value;
    const stockDisponible = document.getElementById('stockDisponible').value;
    const nombreProducto = document.getElementById('nombreProducto').value;

    if (cantidad > stockDisponible) {
        Swal.fire({
            icon: 'error',
            title: 'Stock insuficiente',
            text: 'No hay suficiente stock disponible para esta cantidad.',
        });
    } else {
        Swal.fire({
            title: '¿Agregar al carrito?',
            text: `Estás a punto de añadir ${cantidad} unidad(es) de "${nombreProducto}" al carrito.`,
            icon: 'warning',
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Sí, añadir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('carritoForm').submit();
            }
        });
    }
});