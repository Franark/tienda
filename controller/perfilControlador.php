<?php
ini_set('display_errors', 1);
require_once('../model/persona.php');
session_start();

// Actualizar información de la persona
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idPersona = $_POST['idPersona'];
    $nombrePersona = $_POST['nombrePersona'];
    $apellidoPersona = $_POST['apellidoPersona'];
    $edadPersona = $_POST['edadPersona'];
    $tipoSexo = $_POST['tipoSexo'];
    $tipoDocumento = $_POST['idTipoDocumento'];
    $valorDocumento = $_POST['valorDocumento'];
    $contactos = isset($_POST['contactos']) ? $_POST['contactos'] : [];

    // Crear instancia de Persona y configurar sus propiedades
    $persona = new Persona();
    $persona->setIdPersona($idPersona);
    $persona->setNombrePersona($nombrePersona);
    $persona->setApellidoPersona($apellidoPersona);
    $persona->setEdadPersona($edadPersona);
    $persona->setTipoSexo_idTipoSexo($tipoSexo);
    $persona->setTipoDocumento($tipoDocumento);
    $persona->setValorDocumento($valorDocumento);

    // Configurar contactos
    $contactosArray = [];
    foreach ($contactos as $contacto) {
        $contactosArray[] = [
            'tipoContacto_idTipoContacto' => $contacto['tipoContacto_idTipoContacto'],
            'valor' => $contacto['valor']
        ];
    }
    $persona->setContactos($contactosArray);

    // Actualizar persona
    $persona->actualizarPersona();

    header('Location: ../?page=perfil');
    exit();
}

// Eliminar contacto de la persona
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
