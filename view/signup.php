<h1 class="signup-titulo">Registrarse</h1>
<form action="controller/registroControlador.php" method="post">
    <div class="form-group">
        <i><img src="assets/images/icons8-user-24.png" alt="user" class="icon user"></i>
        <input type="text" name="nickname" id="nickname" placeholder="Nombre de usuario" required>
    </div>
    <div class="form-group">
        <i><img src="assets/images/email.png" alt="email" class="icon email"></i>
        <input type="email" name="email" id="email" placeholder="Email" required>
    </div>
    <div class="form-group">
        <i><img src="assets/images/icons8-eye-30.png" alt="eye" class="icon password" id="pass"></i>            
        <input type="password" name="password" id="password" placeholder="Contraseña" required>
    </div>
    <div class="form-group">
        <i><img src="assets/images/icons8-eye-30.png" alt="eye" class="icon password" id="passConf"></i>            
        <input type="password" name="passwordConf" id="passwordConf" placeholder="Confirmar Contraseña" required>
    </div>
    <div class="form-group">
        <label for="rolUsuario_idRolUsuario">Rol:</label>
        <select name="rolUsuario_idRolUsuario" id="rolUsuario_idRolUsuario" required>
            <?php
            require_once('model/rolUsuario.php');
            $rolUsuario = new RolUsuario();
            $roles = $rolUsuario->listarRoles();
            
            foreach ($roles as $rol) {
                echo "<option class='opcion' value='{$rol['idRolUsuario']}'>{$rol['nombreRol']}</option>";
            }
            ?>
        </select>
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="boton" value="Crear cuenta">
    </div>
    <p class="parrafo">¿Ya tienes una cuenta? <a href="./?page=login"><strong>Iniciar Sesión</strong></a></p>
</form>