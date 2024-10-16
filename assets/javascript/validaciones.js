document.getElementById("crearCategoriaForm").addEventListener("submit", function(event) {
    var nombreCategoria = document.getElementById("nombreCategoria").value.trim();

    if (nombreCategoria === "") {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la categoría no puede estar vacío.',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (nombreCategoria.length > 50) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la categoría no puede exceder los 50 caracteres.',
            confirmButtonText: 'OK'
        });
        return;
    }
});
function confirmarEliminacion(idCategoriaProducto) {
    Swal.fire({
        title: '¿Está seguro?',
        text: 'Una vez eliminada, no podrá recuperar esta categoría.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'controller/categoriaControlador.php?accion=eliminar&idCategoriaProducto=' + idCategoriaProducto;
        }
    });
}
document.getElementById('editarCategoriaForm').addEventListener('submit', function(event) {
    const nombreCategoria = document.getElementById('nombreCategoria').value.trim();

    if (nombreCategoria === '') {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la categoría no puede estar vacío.',
            confirmButtonText: 'OK'
        });
    } else if (nombreCategoria.length > 50) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la categoría no puede exceder los 50 caracteres.',
            confirmButtonText: 'OK'
        });
    }
});

document.getElementById("crearMarcaForm").addEventListener("submit", function(event) {
    var nombreMarca = document.getElementById("nombreMarca").value.trim();

    if (nombreMarca === "") {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la marca no puede estar vacío.',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (nombreMarca.length > 50) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la marca no puede exceder los 50 caracteres.',
            confirmButtonText: 'OK'
        });
        return;
    }
});

function confirmarEliminacion(idMarca) {
    Swal.fire({
        title: '¿Está seguro?',
        text: 'Una vez eliminada, no podrá recuperar esta marca.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'controller/marcaControlador.php?accion=eliminar&idMarca=' + idMarca;
        }
    });
}

document.getElementById('editarMarcaForm').addEventListener('submit', function(event) {
    const nombreMarca = document.getElementById('nombreMarca').value.trim();

    if (nombreMarca === '') {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la marca no puede estar vacío.',
            confirmButtonText: 'OK'
        });
    } 
    else if (nombreMarca.length > 50) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre de la marca no puede exceder los 50 caracteres.',
            confirmButtonText: 'OK'
        });
    }
});

const today = new Date().toISOString().split('T')[0];
document.getElementById('fechaVencimiento').setAttribute('min', today);

document.getElementById('crearProductoForm').addEventListener('submit', function (event) {
    let nombreProducto = document.getElementById('nombreProducto').value.trim();
    let codigoBarras = document.getElementById('codigoBarras').value.trim();
    let precio = document.getElementById('precio').value;
    let stock = document.getElementById('stock').value;
    let fechaVencimiento = document.getElementById('fechaVencimiento').value;

    if (!nombreProducto || !codigoBarras || !precio || !stock || !fechaVencimiento) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Campos vacíos',
            text: 'Por favor, rellena todos los campos antes de enviar el formulario.',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (nombreProducto === codigoBarras) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El nombre del producto no puede ser igual al código de barras.',
            confirmButtonText: 'OK'
        });
        return;
    }

    let fechaActual = new Date().toISOString().split('T')[0];
    if (fechaVencimiento < fechaActual) {
        event.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Fecha inválida',
            text: 'La fecha de vencimiento no puede ser anterior a la fecha actual.',
            confirmButtonText: 'OK'
        });
        return;
    }
});