<header>
    <h1>Editar Barrio</h1>
</header>
<main>
   <?php
    require_once('model/barrio.php');

    $idBarrio = $_GET['idBarrio'];
    $barrio = new Barrio();
    $barrio = $barrio->obtenerBarrioPorId($idBarrio);

    if (isset($_GET['error'])): ?>
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

    <form id="editarBarrioForm" action="controller/barrioControlador.php" method="post">
        <input type="hidden" name="idBarrio" value="<?php echo $barrio['idBarrio'];?>">
        <label for="nombreBarrio">Nombre del Barrio:</label>
        <input type="text" id="nombreBarrio" name="nombreBarrio" value="<?php echo htmlspecialchars($barrio['nombreBarrio']);?>">
        <br>
        <input type="submit" name="editar" value="Actualizar">
    </form>
</main>
