<?php
require_once('../model/usuario.php');

$id = $_GET['id'];

$usuario = new Usuario();
$usuario->setId($id);

header('Location: ../view/gestionUsuarios.php');
?>
