<header>
    <h1>Crear Tipo de Contacto</h1>
</header>
<main>
    <form action="controller/tipoContactoControlador.php" method="POST">
        <div>
            <label for="nombreTipoContacto">Nombre:</label>
            <input type="text" id="nombreTipoContacto" name="nombreTipoContacto" required>
        </div>
        <div>
            <button type="submit" name="crear">Crear Tipo de Contacto</button>
        </div>
    </form>
</main>