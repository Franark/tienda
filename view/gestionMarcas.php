<header>
    <h1>Gestión de Marcas</h1>
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
            require_once('model/marca.php');

            $marca = new Marca();
            $marcas = $marca->listarMarcas();

            foreach ($marcas as $m) {
                echo "<tr>";
                echo "<td>{$m['idMarca']}</td>";
                echo "<td>{$m['nombreMarca']}</td>";
                echo "<td><a href='?page=editarMarca&idMarca={$m['idMarca']}'>Editar</a> | <a href='controller/marcaControlador.php?accion=eliminar&idMarca={$m['idMarca']}' onclick='return confirm(\"¿Está seguro de eliminar este tipo de marca?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="?page=crearMarca">Crear Nueva Marca</a>
</main>