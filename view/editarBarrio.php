<header>
    <h1>Editar Barrio</h1>
</header>
<main>
    <?php
    require_once('model/barrio.php');

    $idBarrio = $_GET['idBarrio'];
    $tipoDocumento = new TipoDocumento();
    $documento = $tipoDocumento->obtenerTipoDocumentoPorId($idBarrio);

    if ($documento) {
        echo "<form action='controller/tipoDocumentoControlador.php' method='POST'>";
        echo "<input type='hidden' name='idBarrio' value='{$documento['idBarrio']}'>";
        echo "<label for='nombreTipoDocumento'>Nombre:</label>";
        echo "<input type='text' id='nombreTipoDocumento' name='nombreTipoDocumento' value='{$documento['nombreTipoDocumento']}' required><br><br>";
        echo "<button type='submit' name='actualizar'>Actualizar Tipo de Documento</button>";
        echo "</form>";
    } else {
        echo "<p>Documento no encontrado.</p>";
    }
    ?>
</main>