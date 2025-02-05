<header>
    <h1>Editar Producto</h1>
</header>
<main>
    <?php
    require_once('model/producto.php');
    require_once('model/marca.php');
    require_once('model/categoriaProducto.php');
    
    $id = $_GET['idProducto'];
    $producto = new Producto();
    $p = $producto->obtenerProductoPorId($id);
    ?>
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
    <form id="editarProductoForm" action="controller/productoControlador.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idProducto" value="<?php echo $p['idProducto']; ?>">
        
        <label for="nombreProducto">Nombre del Producto:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $p['nombreProducto']; ?>">
        <br>
        
        <label for="codigoBarras">Código de Barra:</label>
        <input type="number" id="codigoBarras" name="codigoBarras" value="<?php echo $p['codigoBarras']; ?>">
        <br>
        
        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" value="<?php echo $p['precio']; ?>">
        <br>
        
        <label for="stock">Stock:</label>
        <input type="number" id="stock" name="stock" value="<?php echo $p['stock']; ?>">
        <br>
        
        <label for="fechaVencimiento">Fecha de Vencimiento:</label>
        <input type="date" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo $p['fechaVencimiento']; ?>">
        <br>
        <label>Imágenes actuales:</label>
        <?php 
        $imagenes = $producto->obtenerImagenesPorProducto($id);
        if (!empty($imagenes)): ?>
            <div id="imagenesActuales">
                <?php foreach ($imagenes as $imagen): ?>
                    <div class="imagen-item">
                        <img src="/tienda/assets/<?php echo $imagen['imagen']; ?>" alt="Imagen" width="100">
                        <label>
                            <input type="checkbox" name="eliminarImagenes[]" value="<?php echo $imagen['imagen']; ?>">
                            Eliminar
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No hay imágenes subidas para este producto.</p>
        <?php endif; ?>

        <label for="nuevasImagenes">Añadir nuevas imágenes:</label>
        <input type="file" id="nuevasImagenes" name="nuevasImagenes[]" multiple>
        <label for="marca_idMarca">Marca:</label>
        <select id="marca_idMarca" name="marca_idMarca">
            <?php
            $marca = new Marca();
            $marcas = $marca->listarMarcas();
            
            foreach ($marcas as $m) {
                $selected = ($m['idMarca'] == $p['marca_idMarca']) ? "selected" : "";
                echo "<option value='{$m['idMarca']}' {$selected}>{$m['nombreMarca']}</option>";
            }
            ?>
        </select>
        <br>
        <label for="categoriaProducto_idCategoriaProducto">Categoría:</label>
        <select id="categoriaProducto_idCategoriaProducto" name="categoriaProducto_idCategoriaProducto">
            <?php
            $categoria = new CategoriaProducto();
            $categorias = $categoria->listarCategorias();
            
            foreach ($categorias as $c) {
                $selected = ($c['idCategoriaProducto'] == $p['categoriaProducto_idCategoriaProducto']) ? "selected" : "";
                echo "<option value='{$c['idCategoriaProducto']}' {$selected}>{$c['nombreCategoria']}</option>";
            }
            ?>
        </select>
        <br>
        <button type="submit" name="editar">Actualizar Producto</button>
    </form>
</main>

<script>
const today = new Date().toISOString().split('T')[0];
document.getElementById('fechaVencimiento').setAttribute('min', today);

document.getElementById('editarProductoForm').addEventListener('submit', function (event) {
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
