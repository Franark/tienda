<header>
    <h1>Crear Usuario</h1>
</header>
<main>
    <form action="guardarUsuario.php" method="post">
        <label for="nickname">Nickname:</label>
        <input type="text" id="nickname" name="nickname" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="rolUsuario_idRolUsuario">Rol:</label>
        <select id="rolUsuario_idRolUsuario" name="rolUsuario_idRolUsuario" required>
            <?php
            require_once('model/rolUsuario.php');
            $rolUsuario = new RolUsuario();
            $roles = $rolUsuario->listarRoles();
            
            foreach ($roles as $rol) {
                echo "<option value='{$rol['idRolUsuario']}'>{$rol['nombreRol']}</option>";
            }
            ?>
        </select>
        <br>
        <br>
        <input type="submit" class="boton" value="Crear">
    </form>
</main>