<header>
    <h1>Editar Producto</h1>
</header>
<main>
    <?php
    require_once('../model/producto.php');
    require_once('../model/marca.php');
    require_once('../model/categoriaProducto.php');
    
    $id = $_GET['idProducto'];
    $producto = new Producto();
    $p = $producto->obtenerProductoPorId($id);
    ?>
    <form action="../controller/productoControlador.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="idProducto" value="<?php echo $p['idProducto']; ?>">
        <label for="nombreProducto">Nombre del Producto:</label>
        <input type="text" id="nombreProducto" name="nombreProducto" value="<?php echo $p['nombreProducto']; ?>" required>
        <br>
        <label for="codigoBarra">Código de Barra:</label>
        <input type="text" id="codigoBarra" name="codigoBarra" value="<?php echo $p['codigoBarra']; ?>" required>
        <br>
        <label for="precio">Precio:</label>
        <input type="text" id="precio" name="precio" value="<?php echo $p['precio']; ?>" required>
        <br>
        <label for="stock">Stock:</label>
        <input type="text" id="stock" name="stock" value="<?php echo $p['stock']; ?>" required>
        <br>
        <label for="fechaVencimiento">Fecha de Vencimiento:</label>
        <input type="date" id="fechaVencimiento" name="fechaVencimiento" value="<?php echo $p['fechaVencimiento']; ?>" required>
        <br>
        <label for="imagen">Imagen:</label>
        <input type="file" id="imagen" name="imagen">
        <input type="hidden" name="imagenActual" value="<?php echo $p['imagen']; ?>">
        <br>
        <label for="marca_idMarca">Marca:</label>
        <select id="marca_idMarca" name="marca_idMarca" required>
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
        <select id="categoriaProducto_idCategoriaProducto" name="categoriaProducto_idCategoriaProducto" required>
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
        <input type="submit" name="editar" value="Actualizar">
    </form>
</main>
