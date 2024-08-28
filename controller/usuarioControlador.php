<?php
require_once('../model/usuario.php');

$nickname = $_POST['nickname'];
$email = $_POST['email'];
$password = $_POST['password'];
$passwordConf = $_POST['passwordConf'];
$rolUsuario_idRolUsuario = $_POST['rolUsuario_idRolUsuario'];

if ($password !== $passwordConf) {
    header('Location: signup.php?error=Las contraseñas no coinciden');
    exit();
}

$usuario = new Usuario();
$usuario->setNickname($nickname);
$usuario->setEmail($email);
$usuario->setPassword(password_hash($password, PASSWORD_DEFAULT));
$usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);

if ($usuario->guardar()) {
    header('Location: login.php');
} else {
    header('Location: signup.php?error=Error al crear la cuenta');
}
?>
