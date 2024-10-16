<?php
ini_set('display_errors', 1);
require_once('../model/persona.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idPersona = $_POST['idPersona'];
    $nombrePersona = $_POST['nombrePersona'];
    $apellidoPersona = $_POST['apellidoPersona'];
    $edadPersona = $_POST['edadPersona'];
    $tipoSexo = $_POST['tipoSexo'];
    $tipoDocumento = $_POST['idTipoDocumento'];
    $valorDocumento = $_POST['valorDocumento'];
    $contactos = isset($_POST['contactos']) ? $_POST['contactos'] : [];

    $persona = new Persona();
    $persona->setIdPersona($idPersona);
    $persona->setNombrePersona($nombrePersona);
    $persona->setApellidoPersona($apellidoPersona);
    $persona->setEdadPersona($edadPersona);
    $persona->setTipoSexo_idTipoSexo($tipoSexo);
    $persona->setTipoDocumento($tipoDocumento);
    $persona->setValorDocumento($valorDocumento);

    $contactosArray = [];
    foreach ($contactos as $contacto) {
        $contactosArray[] = [
            'tipoContacto_idTipoContacto' => $contacto['tipoContacto_idTipoContacto'],
            'valor' => $contacto['valor']
        ];
    }
    $persona->setContactos($contactosArray);

    $persona->actualizarPersona();

    header('Location: ../?page=perfil');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminarContacto'])) {
    $contactoId = $_POST['contactoId'];

    $persona = new Persona();
    $resultado = $persona->eliminarContacto($contactoId);

    if ($resultado) {
        header('Location: ../?page=perfil');
    } else {
        echo "Error al eliminar el contacto.";
    }
    exit();
}
?>
