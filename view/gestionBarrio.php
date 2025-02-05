<header>
    <h1>Gestión de AtributoDomicilios</h1>
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
    <?php elseif (isset($_GET['success'])): ?>
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
            require_once('model/domicilio.php');
            $barrio = new Domicilio();
            $barrios = $barrio->listarAtributosDomicilio();

            foreach ($barrios as $b) {
                echo "<tr>";
                echo "<td>{$b['idAtributoDomicilio']}</td>";
                echo "<td>{$b['nombreAtributo']}</td>";
                echo "<td><a href='?page=editarAtributoDomicilio&idAtributoDomicilio={$b['idAtributoDomicilio']}'>Editar</a> | <a href='controller/barrioControlador.php?accion=eliminar&idAtributoDomicilio={$b['idAtributoDomicilio']}'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearAtributoDomicilio">Crear Nueva Categoria</a>
</main>