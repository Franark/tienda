<header>
    <h1>Editar Nombre Atributo</h1>
</header>
<main>
    <?php
    require_once('model/domicilio.php');
    $idAtributoDomicilio = $_GET['idAtributoDomicilio'];
    $domicilio = new Domicilio();
    $domicilio = $domicilio->obtenerAtributoDomicilioPorId($idAtributoDomicilio);
    $nombreAtributo = $domicilio['nombreAtributo'];
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
    <form action="controller/domicilioControlador.php" method="POST">
        <input type="hidden" name="idAtributoDomicilio" value="<?php echo $idAtributoDomicilio; ?>">

        <label for="nombreAtributo">Nombre de la nombreAtributo:</label>
        <input type="text" id="nombreAtributo" name="nombreAtributo" value="<?php echo htmlspecialchars($nombreAtributo); ?>">

        <button type="submit" name="editar">Actualizar nombreAtributo</button>
    </form>
</main>
<script src="assets/javascript/validaciones.js"></script>