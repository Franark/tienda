<header>
    <h1>Editar Marca</h1>
</header>
<main>
    <?php
    require_once('model/marca.php');
    $idMarca = $_GET['idMarca'];
    $marca = new Marca();
    $marcaData = $marca->obtenerMarcaPorId($idMarca);
    $nombreMarca = $marcaData['nombreMarca'];
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
    <form action="controller/marcaControlador.php" method="POST" id="editarMarcaForm">
        <input type="hidden" name="idMarca" value="<?php echo $idMarca; ?>">

        <label for="nombreMarca">Nombre de la Marca:</label>
        <input type="text" id="nombreMarca" name="nombreMarca" value="<?php echo htmlspecialchars($nombreMarca); ?>">

        <button type="submit" name="editar">Actualizar Marca</button>
    </form>

    <a href="?page=gestionMarcas">Volver a la gestión de marcas</a>
</main>
<script src="assets/javascript/validaciones.js"></script>