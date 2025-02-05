<header>
    <h1>Gestión de Tipos de Contactos</h1>
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
            require_once('model/tipoContacto.php');
            $tipoContacto = new TipoContacto();
            $tiposContacto = $tipoContacto->listarTipoContacto();

            foreach ($tiposContacto as $contacto) {
                echo "<tr>";
                echo "<td>{$contacto['idTipoContacto']}</td>";
                echo "<td>{$contacto['nombreTipoContacto']}</td>";
                echo "<td><a href='?page=editarTipoContacto&idTipoContacto={$contacto['idTipoContacto']}'>Editar</a> | <a href='controller/tipoContactoControlador.php?accion=eliminar&idTipoContacto={$contacto['idTipoContacto']}' onclick='return confirm(\"¿Está seguro de eliminar este tipo de Contacto?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a class="boton" href="?page=crearTipoContacto">Crear Nuevo Tipo de Contacto</a>
</main>