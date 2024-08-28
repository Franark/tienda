<header>
    <h1>Editar Tipo de Documento</h1>
</header>
<main>
    <?php
    require_once('model/tipoDocumento.php');

    $idTipoDocumento = $_GET['idTipoDocumento'];
    $tipoDocumento = new TipoDocumento();
    $documento = $tipoDocumento->obtenerTipoDocumentoPorId($idTipoDocumento);

    if ($documento) {
        echo "<form action='controller/tipoDocumentoControlador.php' method='POST'>";
        echo "<input type='hidden' name='idTipoDocumento' value='{$documento['idTipoDocumento']}'>";
        echo "<label for='nombreTipoDocumento'>Nombre:</label>";
        echo "<input type='text' id='nombreTipoDocumento' name='nombreTipoDocumento' value='{$documento['nombreTipoDocumento']}' required><br><br>";
        echo "<button type='submit' name='actualizar'>Actualizar Tipo de Documento</button>";
        echo "</form>";
    } else {
        echo "<p>Documento no encontrado.</p>";
    }
    ?>
</main>