<?php
require_once('../model/usuario.php');

$idUsuario = $_GET['id'];

if (isset($_GET['id'])) {
    $idUsuario = $_GET['id'];
} else {
    header('Location: ../?page=gestionUsuarios&error=ID no proporcionado');
    exit;
}


$usuario = new Usuario();
if ($usuario->eliminar($idUsuario)) {
    header('Location: ../?page=gestionUsuarios&mensaje=eliminado');
} else {
    echo "Error al eliminar el usuario con ID $idUsuario";
    exit;
}
?>
