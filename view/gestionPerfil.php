<header>
    <h1>Gestionar Perfil</h1>
</header>
<main>
    <?php if (isset($_GET['error'])): ?>
        <p style="color: red;"><?php echo $_GET['error']; ?></p>
    <?php endif; ?>
    <form action="../controller/personaControlador.php" method="POST">
        <div>
            <label for="nombrePersona">Nombre:</label>
            <input type="text" id="nombrePersona" name="nombrePersona" required>
        </div>
        <div>
            <label for="apellidoPersona">Apellido:</label>
            <input type="text" id="apellidoPersona" name="apellidoPersona" required>
        </div>
        <div>
            <label for="edadPersona">Edad:</label>
            <input type="number" id="edadPersona" name="edadPersona" required>
        </div>
        <div>
            <label for="tipoSexo_idTipoSexo">Sexo:</label>
            <select id="tipoSexo_idTipoSexo" name="tipoSexo_idTipoSexo" required>
                <?php
                require_once('../model/tipoSexo.php');
                $tipoSexoModel = new TipoSexo();
                $tiposSexo = $tipoSexoModel->listarTiposSexo();
                foreach ($tiposSexo as $sexo) {
                    echo "<option value='{$sexo['idTipoSexo']}'>{$sexo['nombreTipoSexo']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="tipoDocumento_idTipoDocumento">Tipo de Documento:</label>
            <select id="tipoDocumento_idTipoDocumento" name="tipoDocumento_idTipoDocumento" required>
                <?php
                require_once('../model/tipoDocumento.php');
                $tipoDocumentoModel = new TipoDocumento();
                $tiposDocumento = $tipoDocumentoModel->listarTiposDocumento();
                foreach ($tiposDocumento as $documento) {
                    echo "<option value='{$documento['idTipoDocumento']}'>{$documento['nombreTipoDocumento']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="valorDocumento">Número de Documento:</label>
            <input type="text" id="valorDocumento" name="valorDocumento" required>
        </div>
        <div>
            <label for="tipoContacto_idTipoContacto">Tipo de Contacto:</label>
            <select id="tipoContacto_idTipoContacto" name="tipoContacto_idTipoContacto" required>
                <?php
                require_once('../model/tipoContacto.php');
                $tipoContactoModel = new TipoContacto();
                $tiposContacto = $tipoContactoModel->listarTiposContacto();
                foreach ($tiposContacto as $contacto) {
                    echo "<option value='{$contacto['idTipoContacto']}'>{$contacto['nombreTipoContacto']}</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label for="valorContacto">Valor de Contacto:</label>
            <input type="text" id="valorContacto" name="valorContacto" required>
        </div>
        <div>
            <button type="submit" name="crear">Crear Persona</button>
        </div>
    </form>
</main>
