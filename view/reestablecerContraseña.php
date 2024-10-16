<?php
$email = isset($_GET['email']) ? $_GET['email'] : null;
$token = isset($_GET['token']) ? $_GET['token'] : null;

if (!$email || !$token) {
    $error = "No se ha recibido un token o un correo electrónico válido.";
} else {
    $error = isset($_GET['error']) ? $_GET['error'] : null;
}
?>
<h1>Restablecer Contraseña</h1>

<?php if ($error): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo htmlspecialchars($error); ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = './?page=login';
            }
        });
    </script>
<?php endif; ?>

<form id="resetForm" action="controller/procesarCambioPassword.php" method="POST">
    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

    <label for="nuevaContraseña">Nueva Contraseña:</label>
    <input type="password" id="nuevaContraseña" name="nuevaContraseña">
    
    <label for="confirmarContraseña">Confirmar Nueva Contraseña:</label>
    <input type="password" id="confirmarContraseña" name="confirmarContraseña">

    <button type="submit">Cambiar Contraseña</button>
</form>

<script src="assets/javascript/reestablecerContraseña.js"></script>
