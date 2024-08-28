<header>
    <h1>Editar Marca</h1>
</header>
<main>
    <?php
    require_once('../model/marca.php');

    if (isset($_GET['idMarca'])) {
        $id = $_GET['idMarca'];
        $marca = new Marca();
        $m = $marca->obtenerMarcaPorId($id);
        
        if ($m) {
    ?>
            <form action="../controller/marcaControlador.php" method="post">
                <input type="hidden" name="idMarca" value="<?php echo $m['idMarca']; ?>">
                <label for="nombreMarca">Nombre de la Marca:</label>
                <input type="text" id="nombreMarca" name="nombreMarca" value="<?php echo $m['nombreMarca']; ?>" required>
                <br>
                <input type="submit" name="editar" value="Actualizar">
            </form>
    <?php
        } else {
            echo "<p>Marca no encontrada.</p>";
        }
    } else {
        echo "<p>ID de marca no especificado.</p>";
    }
    ?>
</main>