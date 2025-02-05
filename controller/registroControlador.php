<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/usuario.php');

$nickname = $_POST['nickname'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordConf = $_POST['passwordConf'];
$rolUsuario_idRolUsuario = 2;

if ($password !== $passwordConf) {
    header('location: ../?page=signup&error=Las contraseñas no coinciden');
    exit();
}

$usuario = new Usuario();
$usuario->setNickname($nickname);
$usuario->setEmail($email);
$usuario->setPassword($password);
$usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);

if ($usuario->buscarPorNickname($nickname)) {
    header('Location: ../?page=signup&error=El nickname ya está en uso');
    exit();
}

if ($usuario->buscarPorEmail($email)) {
    header('Location: ../?page=signup&error=El email ya está registrado');
    exit();
}

$token = bin2hex(random_bytes(16));

if ($usuario->guardar()) {
    if ($usuario->guardarTokenConfirmacion($token)) {
        $userEmail = $email;
        require 'enviarMensaje.php';
    } else {
        header('Location: ../?page=signup&error=Error al generar el token');
    }
} else {
    header('Location: ../?page=signup&error=Error al registrar el usuario');
}
?>
