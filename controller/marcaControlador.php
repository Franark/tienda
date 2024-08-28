<?php
require_once('../model/marca.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreMarca = $_POST['nombreMarca'];

    $marca = new Marca();
    $marca->setNombreMarca($nombreMarca);

    if ($marca->crearMarca()) {
        header('Location: ../?page=gestionMarcas');
    } else {
        header('Location: ../?page=crearMarca&error=Error al crear la marca');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $idMarca = $_POST['idMarca'];
    $nombreMarca = $_POST['nombreMarca'];

    $marca = new Marca();
    $marca->setIdMarca($idMarca);
    $marca->setNombreMarca($nombreMarca);

    if ($marca->actualizarMarca($idMarca, $nombreMarca)) {
        header('Location: ../?page=gestionMarcas');
    } else {
        header('Location: ../?page=editarMarca&idMarca=' . $idMarca . '&error=Error al actualizar la marca');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idMarca = $_GET['idMarca'];

    $marca = new Marca();
    if ($marca->eliminarMarca($idMarca)) {
        header('Location: ../?page=gestionMarcas');
    } else {
        header('Location: ../?page=gestionMarcas&error=Error al eliminar la marca');
    }
}
?>
