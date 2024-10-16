<header>
    <h1>Gestión de Categorías</h1>
</header>
<main>
    <?php if (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo htmlspecialchars($_GET['success']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
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
                echo "<td>
                        <a href='?page=editarCategoria&idCategoriaProducto={$c['idCategoriaProducto']}'>Editar</a> | 
                        <a href='#' onclick='confirmarEliminacion({$c['idCategoriaProducto']})'>Eliminar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearCategoria">Crear Nueva Categoría</a>
</main>
<script src="assets/javascript/validaciones.js"></script>