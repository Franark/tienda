<header>
    <h1>Añadir Tipo de Documento</h1>
</header>
<main>
    <form id="crearTipoDocumentoForm" action="controller/tipoDocumentoControlador.php" method="POST">
        <label for="nombreTipoDocumento">Nombre:</label>
        <input type="text" id="nombreTipoDocumento" name="nombreTipoDocumento" required><br><br>
        
        <button type="submit" name="crear">Crear Tipo de Documento</button>
    </form>
</main>

<script>
    document.getElementById('crearTipoDocumentoForm').addEventListener('submit', function(event) {
        const nombreTipoDocumento = document.getElementById('nombreTipoDocumento').value;
        
        if (!nombreTipoDocumento) {
            alert('Por favor, completa el campo "Nombre" antes de continuar.');
            document.getElementById('nombreTipoDocumento').focus();
            event.preventDefault();
        }
    });
</script>
