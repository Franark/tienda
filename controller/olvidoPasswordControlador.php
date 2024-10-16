<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/usuario.php');
require_once('enviarCambioPassword.php');

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $usuario = new Usuario();
    $usuarioInfo = $usuario->obtenerUsuarioPorEmail($email);

    if ($usuarioInfo) {
        $token = bin2hex(random_bytes(16));
        $usuario->setEmail($email);
        $usuario->guardarTokenConfirmacion($token);

        $resetLink = "https://localhost/tienda/controller/reestablecerPassword.php?token=$token&email=$email";

        enviarCorreoCambioPassword($email, $resetLink);

        header('Location: ../?page=login&success=Se ha enviado un correo para la recuperación de la contraseña.');
        exit;
    } else {
        header('Location: ../?page=login&error=El correo ingresado no está registrado en el sistema.');
        exit;
    }
} else {
    header('Location: ../?page=login&error=No se recibió ningún correo electrónico.');
    exit;
}
?>
