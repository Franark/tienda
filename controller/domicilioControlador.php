<?php
require_once('../model/domicilio.php');

if(isset($_POST['crear'])){
    $nombreAtributo=$_POST['nombreAtributo'];
    $domicilio = new Domicilio();
    $domicilio->setNombreAtributo($nombreAtributo);
    if($domicilio->crearAtributoDomicilio()){
        header('Location:../?page=gestionDomicilio&success=Atributo domicilio creado correctamente.');
    } else {
        header('Location:../?page=crearDomicilio&error=Error al crear el atributo domicilio');
    }
}

if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar') {
    $idDomicilio = $_GET['idAtributoDomicilio'];
    $domicilio = new Domicilio();

    if ($domicilio->eliminarAtributoDomicilio($idDomicilio)) {
        header('Location: ../?page=gestionDomicilio&success=Atributo domicilio eliminado correctamente.');
    } else {
        header('Location: ../?page=gestionDomicilio&error=Error al eliminar el atributo domicilio.');
    }
}

if(isset($_POST['editar'])){
    $idAtributoDomicilio = $_POST['idAtributoDomicilio'];
    $nombreAtributo = $_POST['nombreAtributo'];
    $domicilio = new Domicilio($idAtributoDomicilio);
    $domicilio->setNombreAtributo($nombreAtributo);
    if($domicilio->editarAtributoDomicilio($idAtributoDomicilio)){
        header('Location:../?page=gestionDomicilio&success=Atributo domicilio editado correctamente.');
    } else {
        header('Location:../?page=gestionDomicilio&error=Error al editar el atributo domicilio');
    }
}
?>