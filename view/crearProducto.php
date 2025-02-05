<header>
    <h1>Crear Producto</h1>
</header>
<main>
    <?php if (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php elseif (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo htmlspecialchars($_GET['success']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    <form id="crearProductoForm" action="controller/productoControlador.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombreProducto">Nombre del Producto:</label>
            <input type="text" id="nombreProducto" name="nombreProducto">
        </div>
        <div class="form-group">
            <label for="codigoBarras">Código de Barra:</label>
            <input type="number" id="codigoBarras" name="codigoBarras">
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio">
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock">
        </div>
        <div class="form-group">
            <label for="fechaVencimiento">Fecha de Vencimiento:</label>
            <input type="date" id="fechaVencimiento" name="fechaVencimiento">
        </div>
        <div class="form-group">
            <label for="imagenes">Imágenes:</label>
            <input type="file" id="imagenes" name="imagenes[]" accept="image/*" multiple>
        </div>
        <div class="form-group">
            <label for="marca_idMarca">Marca:</label>
            <select id="marca_idMarca" name="marca_idMarca">
                <?php
                require_once('model/marca.php');
                $marca = new Marca();
                $marcas = $marca->listarMarcas();
                
                foreach ($marcas as $m) {
                    echo "<option value='{$m['idMarca']}'>{$m['nombreMarca']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="categoriaProducto_idCategoriaProducto">Categoría:</label>
            <select id="categoriaProducto_idCategoriaProducto" name="categoriaProducto_idCategoriaProducto">
                <?php
                require_once('model/categoriaProducto.php');
                $categoria = new CategoriaProducto();
                $categorias = $categoria->listarCategorias();
                
                foreach ($categorias as $c) {
                    echo "<option value='{$c['idCategoriaProducto']}'>{$c['nombreCategoria']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" name="crear">Crear Producto</button>
        </div>
    </form>
</main>
<script>
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
</script>