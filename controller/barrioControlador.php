<?php
require_once('../model/barrio.php');

if(isset($_POST['crear'])){
    $nombreBarrio = $_POST['nombreBarrio'];

    $barrio = new Barrio();
    $barrio->setNombreBarrio($nombreBarrio);
    if($barrio->crearBarrio()){
        header('Location:../?page=gestionBarrio&success=Barrio creado correctamente.');
    } else {
        header('Location:../?page=crearBarrio&error=Error al crear el barrio');
    }
}

if(isset($_POST['editar'])){
    $idBarrio = $_POST['idBarrio'];
    $nombreBarrio = $_POST['nombreBarrio'];

    $barrio = new Barrio();
    $barrio->setNombreBarrio($nombreBarrio);
    if($barrio->editarBarrio($idBarrio)){
        header('Location:../?page=gestionBarrio&success=Barrio editado correctamente.');
    } else {
        header('Location:../?page=editarBarrio&error=Error al editar el barrio');
    }
}

if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $idBarrio = $_GET['idBarrio'];

    $barrio = new Barrio();
    if ($barrio->eliminarBarrio($idBarrio)) {
        header('Location:../?page=gestionBarrio&success=Barrio eliminado correctamente.');
    } else {
        header('Location:../?page=gestionBarrio&error=Error al eliminar el barrio.');
    }
    exit();
}

?>