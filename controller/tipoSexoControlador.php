<?php
require_once('../model/tipoSexo.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreTipoSexo = $_POST['nombreTipoSexo'];

    $tipoSexo = new TipoSexo($nombreTipoSexo);

    if ($tipoSexo->crearTipoSexo()) {
        header('Location: ../view/gestionTipoSexo.php');
    } else {
        header('Location: ../view/crearTipoSexo.php?error=Error al crear el tipo de sexo');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idTipoSexo = $_GET['idTipoSexo'];

    $tipoSexo = new TipoSexo();
    if ($tipoSexo->eliminarTipoSexo($idTipoSexo)) {
        header('Location: ../view/gestionTiposSexo.php');
    } else {
        header('Location: ../view/gestionTiposSexo.php?error=Error al eliminar el tipo de sexo');
    }
}
?>
