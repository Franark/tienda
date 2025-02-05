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
<script>
    const urlParams = new URLSearchParams(window.location.search);
    const successMessage = urlParams.get('success');
    const errorMessage = urlParams.get('error');

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: successMessage,
            confirmButtonText: 'Aceptar'
        });
    }

    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage,
            confirmButtonText: 'Aceptar'
        });
    }
</script>
