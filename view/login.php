<?php
if (isset($_SESSION['idUsuario'])) {
    if ($_SESSION['nombreRol'] === 'Administrador') {
        header('Location: ./?page=gestionUsuarios');
        exit();
    } elseif ($_SESSION['nombreRol'] === 'Cliente') {
        header('Location: ./?page=catalogoProductos');
        exit();
    }
}
?>
<header>
    <h1 class="singup-titulo">Iniciar Sesion</h1>
</header>

<?php if (isset($_GET['success'])): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '<?php echo htmlspecialchars($_GET['success']); ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php elseif (isset($_GET['error'])): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo htmlspecialchars($_GET['error']); ?>',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
<form action="controller/loginControlador.php" method="post" onsubmit="return validarFormulario()">
    <div class="input-group">
        <img src="assets/images/icons8-user-24.png" alt="user" class="icon">
        <input type="text" name="nickname" id="nickname" placeholder="Nombre">
    </div>

    <div class="input-group">
        <img src="assets/images/icons8-eye-30.png" alt="eye" class="icon" id="togglePassword">
        <input type="password" name="password" id="password" placeholder="Contraseña">
    </div>

    <button type="submit" name="submit" class="boton">Acceder</button><br><br>

    <a href="./?page=olvidoPassword" class="parrafo">¿Olvidaste tu contraseña?</a>
    <p class="parrafo">¿No tienes una cuenta? <a href="./?page=signup"><strong>Regístrate</strong></a></p>
</form>
<script src="assets/javascript/login.js"></script>
