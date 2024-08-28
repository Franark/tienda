<header>
    <h1>Crear Categoría</h1>
</header>
<main>
    <form action="controller/categoriaControlador.php" method="post">
        <label for="nombreCategoria">Nombre de la Categoría:</label>
        <input type="text" id="nombreCategoria" name="nombreCategoria" required>
        <br>
        <input type="submit" name="crear" class="boton" value="Crear">
    </form>