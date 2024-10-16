<header>
    <h1>Crear Usuario</h1>
</header>
<main>
    <form id="signupForm" action="controller/guardarUsuario.php" method="POST">
        <label for="nickname">Nickname:</label>
        <input type="text" name="nickname" id="nickname" placeholder="Nombre de usuario">
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Correo Electronico">
        <br>
        <label for="rolUsuario_idRolUsuario">Rol:</label>
        <select id="rolUsuario_idRolUsuario" name="rolUsuario_idRolUsuario">
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
<script src="assets/javascript/usuario.js"></script>