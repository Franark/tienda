<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once('../model/usuario.php');

if (isset($_POST['nickname'], $_POST['email'], $_POST['password'], $_POST['passwordConf'], $_POST['rolUsuario_idRolUsuario'])) {
    $nickname = $_POST['nickname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordConf = $_POST['passwordConf'];
    $rolUsuario_idRolUsuario = $_POST['rolUsuario_idRolUsuario'];

    if ($password !== $passwordConf) {
        header('Location: ../?page=signup.php&error=Las contraseñas no coinciden');
        exit();
    }

    $usuario = new Usuario();
    $usuario->setNickname($nickname);
    $usuario->setEmail($email);
    $usuario->setPassword(password_hash($password, PASSWORD_DEFAULT));
    $usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);

    if ($usuario->guardar()) {
        header('Location: ../?page=login');
    } else {
        header('Location: ../?page=signup&error=Error al crear la cuenta');
    }
} else {
    header('Location: ../?page=signup&error=Faltan datos para completar el registro');
    exit();
}

?>
