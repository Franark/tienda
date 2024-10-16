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

    // Actualizar persona
    $persona->actualizarPersona();

    // Actualizar contactos
    if ($contactos) {
        foreach ($contactos as $contacto) {
            if (isset($contacto['id'])) {
                // Actualizar contacto existente
                $persona->actualizarContacto($contacto['id'], $contacto['tipoContacto_idTipoContacto'], $contacto['valor']);
            } else {
                // Agregar nuevo contacto
                $persona->agregarContacto($idPersona, $contacto['tipoContacto_idTipoContacto'], $contacto['valor']);
            }
        }
    }

    header('Location: ../perfil.php');
    exit();
}
?>
