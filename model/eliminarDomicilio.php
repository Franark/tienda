<?php
require_once 'direccion.php';

if (isset($_POST['idDomicilio'])) {
    $idDomicilio = $_POST['idDomicilio'];

    $direccion = new Direccion();

    $resultado = $direccion->eliminarDomicilio($idDomicilio);

    if ($resultado) {
        echo "Domicilio eliminado con éxito.";
    } else {
        echo "Hubo un error al eliminar el domicilio.";
    }
}
