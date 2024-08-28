<header>
    <h1>Crear Marca</h1>
</header>
<main>
    <form action="controller/marcaControlador.php" method="post">
        <label for="nombreMarca">Nombre de la Marca:</label>
        <input type="text" id="nombreMarca" name="nombreMarca" required>
        <br>
        <input type="submit" name="crear" value="Crear">
    </form>
</main>