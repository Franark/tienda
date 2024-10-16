<header>
    <h1>Gestión de Domicilios</h1>
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
            $domicilio = new Domicilio();
            $domicilios = $domicilio->listarAtributosDomicilio();

            foreach ($domicilios as $d) {
                echo "<tr>";
                echo "<td>{$d['idAtributoDomicilio']}</td>";
                echo "<td>{$d['nombreAtributo']}</td>";
                echo "<td>
                        <a href='?page=editarDomicilio&idAtributoDomicilio={$d['idAtributoDomicilio']}'>Editar</a> |
                        <a href='controller/domicilioControlador.php?accion=eliminar&idAtributoDomicilio={$d['idAtributoDomicilio']}' 
                           onclick='return confirm(\"¿Estás seguro de eliminar este domicilio?\");'>Eliminar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearDomicilio">Crear Nueva Categoría</a>
</main>
