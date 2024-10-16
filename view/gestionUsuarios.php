<header>
    <h1>Gestión de Usuarios</h1>
</header>
<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nickname</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once('model/usuario.php');
            $usuario = new Usuario();
            $usuarios = $usuario->listarUsuarios();
            foreach ($usuarios as $u) {
                echo "<tr>";
                echo "<td>{$u['idUsuario']}</td>";
                echo "<td>{$u['nickname']}</td>";
                echo "<td>{$u['email']}</td>";
                echo "<td>{$u['nombreRol']}</td>";
                echo "<td><a href='?page=editarUsuario&id={$u['idUsuario']}'>Editar</a> | <a href='controller/eliminarUsuario.php?id={$u['idUsuario']}' onclick='return confirm(\"¿Está seguro de eliminar este usuario?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <a href="?page=crearUsuario">Crear Nuevo Usuario</a>
</main>