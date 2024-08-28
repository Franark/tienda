<?php
require_once('../model/tipoDocumento.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreTipoDocumento = $_POST['nombreTipoDocumento'];

    $tipoDocumento = new TipoDocumento($nombreTipoDocumento);

    if ($tipoDocumento->crearTipoDocumento()) {
        header('Location: ../?page=gestionTipoDocumento');
    } else {
        header('Location: ../?page=crearTipoDocumento&error=Error al crear el tipo de documento');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idTipoDocumento = $_POST['idTipoDocumento'];
    $nombreTipoDocumento = $_POST['nombreTipoDocumento'];

    $tipoDocumento = new TipoDocumento();
    $tipoDocumento->setIdTipoDocumento($idTipoDocumento);
    $tipoDocumento->setNombreTipoDocumento($nombreTipoDocumento);

    $tipoDocumento->actualizarTipoDocumento();
    header('Location: ../?page=gestionTipoDocumento');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idTipoDocumento = $_GET['idTipoDocumento'];

    $tipoDocumento = new TipoDocumento();
    if ($tipoDocumento->eliminarTipoDocumento($idTipoDocumento)) {
        header('Location: ../?page=gestionTipoDocumento');
    } else {
        header('Location: ../?page=gestionTipoDocumento&error=Error al eliminar el tipo de documento');
    }
}
?>
