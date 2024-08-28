<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once('../model/tipoContacto.php');

$tipoContactoModel = new TipoContacto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['crear'])) {
        $nombreTipoContacto = $_POST['nombreTipoContacto'];
        $tipoContactoModel->crearTipoContacto($nombreTipoContacto);
        header('Location: ../?page=gestionTipoContacto');
    } elseif (isset($_POST['editar'])) {
        $idTipoContacto = $_POST['idTipoContacto'];
        $nombreTipoContacto = $_POST['nombreTipoContacto'];
        $tipoContactoModel->actualizarTipoContacto($idTipoContacto, $nombreTipoContacto);
        header('Location: ../?page=gestionTipoContacto');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion'])) {
    if ($_GET['accion'] === 'eliminar') {
        $idTipoContacto = $_GET['idTipoContacto'];
        $tipoContactoModel->eliminarTipoContacto($idTipoContacto);
        header('Location: ../?page=gestionTipoContacto');
    }
}
?>
