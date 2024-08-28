<header>
    <h1>Crear Producto</h1>
</header>
<main>
    <form action="controller/productoControlador.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombreProducto">Nombre del Producto:</label>
            <input type="text" id="nombreProducto" name="nombreProducto" required>
        </div>
        <div class="form-group">
            <label for="codigoBarras">Código de Barra:</label>
            <input type="text" id="codigoBarras" name="codigoBarras" required>
        </div>
        <div class="form-group">
            <label for="precio">Precio:</label>
            <input type="number" step="0.01" id="precio" name="precio" required>
        </div>
        <div class="form-group">
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" required>
        </div>
        <div class="form-group">
            <label for="fechaVencimiento">Fecha de Vencimiento:</label>
            <input type="date" id="fechaVencimiento" name="fechaVencimiento">
        </div>
        <div class="form-group">
            <label for="imagen">Imagen:</label>
            <input type="file" id="imagen" name="imagen" accept="image/*">
        </div>
        <div class="form-group">
            <label for="marca_idMarca">Marca:</label>
            <select id="marca_idMarca" name="marca_idMarca" required>
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
            <select id="categoriaProducto_idCategoriaProducto" name="categoriaProducto_idCategoriaProducto" required>
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
