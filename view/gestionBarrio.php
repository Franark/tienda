<header>
    <h1>Gestión de Barrios</h1>
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
            require_once('model/barrio.php');
            $barrio = new Barrio();
            $barrios = $barrio->listarBarrios();

            foreach ($barrios as $b) {
                echo "<tr>";
                echo "<td>{$b['idBarrio']}</td>";
                echo "<td>{$b['nombreBarrio']}</td>";
                echo "<td><a href='?page=editarBarrio&idBarrio={$b['idBarrio']}'>Editar</a> | <a href='controller/barrioControlador.php?accion=eliminar&idBarrio={$b['idBarrio']}' onclick='return confirm(\"¿Está seguro de eliminar este tipo de documento?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearBarrio">Crear Nueva Categoria</a>
</main>