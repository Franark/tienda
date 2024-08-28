<header>
    <h1>Añadir Tipo de Documento</h1>
</header>
<main>
    <form action="controller/tipoDocumentoControlador.php" method="POST">
        <label for="nombreTipoDocumento">Nombre:</label>
        <input type="text" id="nombreTipoDocumento" name="nombreTipoDocumento" required><br><br>
        
        <button type="submit" name="crear">Crear Tipo de Documento</button>
    </form>
</main>