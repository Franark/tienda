<header>
    <h1>Editar Usuario</h1>
</header>
<main>
    <?php
    require_once('model/usuario.php');
    require_once('model/rolUsuario.php');
    
    $id = $_GET['idUsuario'];
    $usuario = new Usuario();
    $u = $usuario->obtenerUsuarioPorId($id);
    ?>
    <form action="controller/actualizarUsuario.php" method="post">
        <input type="hidden" name="idUsuario" value="<?php echo $u['idUsuario']; ?>">
        <label for="nickname">Nickname:</label>
        <input type="text" id="nickname" name="nickname" value="<?php echo $u['nickname']; ?>" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $u['email']; ?>" required>
        <br>
        <label for="rolUsuario_idRolUsuario">Rol:</label>
        <select id="rolUsuario_idRolUsuario" name="rolUsuario_idRolUsuario" required>
            <?php
            $rolUsuario = new RolUsuario();
            $roles = $rolUsuario->listarRoles();
            
            foreach ($roles as $rol) {
                $selected = ($rol['idRolUsuario'] == $u['rolUsuario_idRolUsuario']) ? "selected" : "";
                echo "<option value='{$rol['idRolUsuario']}' {$selected}>{$rol['nombreRol']}</option>";
            }
            ?>
        </select>
        <br>
        <input type="submit" value="Actualizar">
    </form>
</main>
