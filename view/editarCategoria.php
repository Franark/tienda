<header>
    <h1>Editar Categoría</h1>
</header>
<main>
    <?php
    require_once('model/categoriaProducto.php');
    
    $id = $_GET['idCategoriaProducto'];
    $categoria = new CategoriaProducto();
    $c = $categoria->obtenerCategoriaPorId($id);
    ?>
    <form action="controller/categoriaControlador.php" method="post">
        <input type="hidden" name="idCategoriaProducto" value="<?php echo $c['idCategoriaProducto']; ?>">
        <label for="nombreCategoria">Nombre de la Categoría:</label>
        <input type="text" id="nombreCategoria" name="nombreCategoria" value="<?php echo $c['nombreCategoria']; ?>" required>
        <br>
        <input type="submit" name="editar" value="Actualizar">
    </form>
</main>