<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('../model/usuario.php');

$id = $_POST['idUsuario'];
$nickname = $_POST['nickname'];
$email = $_POST['email'];
$password = $_POST['password'];
$rolUsuario_idRolUsuario = $_POST['rolUsuario_idRolUsuario'];

$usuario = new Usuario();
$usuario->setId($id);
$usuario->setNickname($nickname);
$usuario->setEmail($email);
$usuario->setPassword($password);
$usuario->setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario);

header('Location: ../?page=gestionUsuarios');
?>