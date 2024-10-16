<header>
    <h1>Crear Tipo de Contacto</h1>
</header>
<main>
    <form id="crearTipoContactoForm" action="controller/tipoContactoControlador.php" method="POST">
        <div>
            <label for="nombreTipoContacto">Nombre:</label>
            <input type="text" id="nombreTipoContacto" name="nombreTipoContacto" required>
        </div>
        <div>
            <button type="submit" name="crear">Crear Tipo de Contacto</button>
        </div>
    </form>
</main>

<script>
    document.getElementById('crearTipoContactoForm').addEventListener('submit', function(event) {
        const nombreTipoContacto = document.getElementById('nombreTipoContacto').value;
        
        if (!nombreTipoContacto) {
            alert('Por favor, completa el campo "Nombre" antes de continuar.');
            document.getElementById('nombreTipoContacto').focus();
            event.preventDefault();
        }
    });
</script>
