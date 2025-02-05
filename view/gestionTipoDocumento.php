<header>
    <h1>Gestión de Tipos de Documento</h1>
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
            require_once('model/tipoDocumento.php');
            $tipoDocumento = new TipoDocumento();
            $tiposDocumento = $tipoDocumento->listarTiposDocumento();

            foreach ($tiposDocumento as $documento) {
                echo "<tr>";
                echo "<td>{$documento['idTipoDocumento']}</td>";
                echo "<td>{$documento['nombreTipoDocumento']}</td>";
                echo "<td><a class='btn btn-primary btn-sm me-2' href='?page=editarTipoDocumento&idTipoDocumento={$documento['idTipoDocumento']}'>Editar</a> <a class='btn-danger' href='controller/tipoDocumentoControlador.php?accion=eliminar&idTipoDocumento={$documento['idTipoDocumento']}' onclick='return confirm(\"¿Está seguro de eliminar este tipo de documento?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearTipoDocumento">Crear Nueva Categoria</a>
</main> 