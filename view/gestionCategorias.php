<header>
    <h1>Gestión de Categorías</h1>
</header>
<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once('model/categoriaProducto.php');

            $categoria = new CategoriaProducto();
            $categorias = $categoria->listarCategorias();

            foreach ($categorias as $c) {
                echo "<tr>";
                echo "<td>{$c['idCategoriaProducto']}</td>";
                echo "<td>{$c['nombreCategoria']}</td>";
                echo "<td>";
                echo "<td><a href='?page=editarCategoria&idCategoriaProducto={$c['idCategoriaProducto']}'>Editar</a> | <a href='controller/categoriaControlador.php?accion=eliminar&idCategoriaProducto={$c['idCategoriaProducto']}' onclick='return confirm(\"¿Está seguro de eliminar esta categoria?\")'>Eliminar</a></td>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearCategoria">Crear Nueva Categoría</a>
</main>