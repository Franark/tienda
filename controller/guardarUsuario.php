<?php
require_once('../model/usuario.php');

$nickname = $_POST['nickname'];
$email = $_POST['email'];
$password = $nickname;
$rolUsuario_idRolUsuario = $_POST['rolUsuario_idRolUsuario'];

$usuario = new Usuario();
$usuario->setNickname($nickname);
$usuario->setEmail($email);
$usuario->setPassword($password);
$usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);
if ($usuario->buscarPorNickname($nickname)) {
    header('Location: ../?page=crearUsuario&error=El nickname ya está en uso');
    exit();
}
if ($usuario->buscarPorEmail($email)) {
    header('Location: ../?page=crearUsuario&error=El email ya está registrado');
    exit();
}

$token = bin2hex(random_bytes(16));

if ($usuario->guardar()) {
    if ($usuario->guardarTokenConfirmacion($token)) {
        $userEmail = $email;
        require 'enviarMensaje.php';
        header('Location: ../?page=crearUsuario&success=Usuario creado con éxito! Correo de confirmación enviado.');
        exit();
    } else {
        header('Location: ../?page=crearUsuario&error=Error al generar el token');
        exit();
    }
} else {
    header('Location: ../?page=crearUsuario&error=Error al registrar el usuario');
    exit();
}

?>