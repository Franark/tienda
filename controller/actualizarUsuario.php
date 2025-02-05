<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/usuario.php');

if (!isset($_POST['idUsuario'], $_POST['nickname'], $_POST['email'], $_POST['rolUsuario_idRolUsuario'])) {
    die('Error: Faltan datos obligatorios.');
}

$id = $_POST['idUsuario'];
$nickname = $_POST['nickname'];
$email = $_POST['email'];
$rolUsuario_idRolUsuario = $_POST['rolUsuario_idRolUsuario'];

$usuario = new Usuario();
$usuario->setId($id);
$usuario->setNickname($nickname);
$usuario->setEmail($email);
$usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);

if ($usuario->actualizar()) {
    header('Location: ../?page=gestionUsuarios');
    exit();
} else {
    echo 'Error al actualizar el usuario. Revisa el registro de errores.';
}
?>
