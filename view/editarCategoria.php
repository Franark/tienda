<header>
    <h1>Editar Categoría</h1>
</header>
<main>
    <?php
    require_once('model/categoriaProducto.php');
    
    $id = $_GET['idCategoriaProducto'];
    $categoria = new CategoriaProducto();
    $c = $categoria->obtenerCategoriaPorId($id);
    $error = '';
    if (isset($_GET['error'])) {
        $error = htmlspecialchars($_GET['error']);
    }
    ?>
    <?php if ($error): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    
    <form id="editarCategoriaForm" action="controller/categoriaControlador.php" method="post">
        <input type="hidden" name="idCategoriaProducto" value="<?php echo $c['idCategoriaProducto']; ?>">
        <label for="nombreCategoria">Nombre de la Categoría:</label>
        <input type="text" id="nombreCategoria" name="nombreCategoria" value="<?php echo htmlspecialchars($c['nombreCategoria']); ?>" maxlength="50">
        <br>
        <input type="submit" name="editar" value="Actualizar">
    </form>
</main>
<script src="assets/javascript/validaciones.js"></script>
