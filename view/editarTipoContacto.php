
    <header>
        <h1>Editar Tipo de Contacto</h1>
    </header>
    <main>
        <?php
        require_once('model/tipoContacto.php');
        $tipoContactoModel = new TipoContacto();
        $idTipoContacto = $_GET['idTipoContacto'];
        $tipoContacto = $tipoContactoModel->obtenerTipoContactoPorId($idTipoContacto);
        ?>
        <form action="controller/tipoContactoControlador.php" method="POST">
            <input type="hidden" name="idTipoContacto" value="<?php echo $tipoContacto['idTipoContacto']; ?>">
            <div>
                <label for="nombreTipoContacto">Nombre:</label>
                <input type="text" id="nombreTipoContacto" name="nombreTipoContacto" value="<?php echo $tipoContacto['nombreTipoContacto']; ?>" required>
            </div>
            <div>
                <button type="submit" name="editar">Actualizar Tipo de Contacto</button>
            </div>
        </form>
    </main>