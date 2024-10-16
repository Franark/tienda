<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

require_once('../model/marca.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreMarca = trim($_POST['nombreMarca']);

    if (empty($nombreMarca)) {
        header('Location: ../?page=crearMarca&error=El nombre de la marca no puede estar vacío.');
        exit();
    }

    $marca = new Marca();
    $marca->setNombreMarca($nombreMarca);

    if ($marca->crearMarca()) {
        header('Location: ../?page=gestionMarcas&success=Marca creada exitosamente.');
    } else {
        header('Location: ../?page=crearMarca&error=Error al crear la marca.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $idMarca = $_POST['idMarca'];
    $nombreMarca = trim($_POST['nombreMarca']);
    
    if (empty($nombreMarca)) {
        header('Location: ../?page=editarMarca&idMarca=' . $idMarca . '&error=El nombre de la marca no puede estar vacío.');
        exit();
    }

    $marca = new Marca();
    $marca->setIdMarca($idMarca);
    $marca->setNombreMarca($nombreMarca);

    if ($marca->actualizarMarca()) {
        header('Location: ../?page=gestionMarcas&success=Marca actualizada exitosamente.');
    } else {
        header('Location: ../?page=editarMarca&idMarca=' . $idMarca . '&error=Error al actualizar la marca.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idMarca = $_GET['idMarca'];

    $marca = new Marca();
    if ($marca->eliminarMarca($idMarca)) {
        header('Location: ../?page=gestionMarcas&success=Marca eliminada exitosamente.');
    } else {
        header('Location: ../?page=gestionMarcas&error=Error al eliminar la marca');
    }
}
?>
