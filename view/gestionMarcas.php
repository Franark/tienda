<header>
    <h1>Gestión de Marcas</h1>
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
            require_once('model/marca.php');

            $marca = new Marca();
            $marcas = $marca->listarMarcas();

            foreach ($marcas as $m) {
                echo "<tr>";
                echo "<td>{$m['idMarca']}</td>";
                echo "<td>{$m['nombreMarca']}</td>";
                echo "<td>
                        <a href='?page=editarMarca&idMarca={$m['idMarca']}'>Editar</a> | 
                        <a href='#' onclick='confirmarEliminacion({$m['idMarca']})'>Eliminar</a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearMarca">Crear Nueva Marca</a>
</main>
<script src="assets/javascript/validaciones.js"></script>