<?php
require_once('model/persona.php');
require_once('model/personaDocumento.php');
require_once('model/personaContacto.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombrePersona = $_POST['nombrePersona'];
    $apellidoPersona = $_POST['apellidoPersona'];
    $edadPersona = $_POST['edadPersona'];
    $tipoSexo_idTipoSexo = $_POST['tipoSexo_idTipoSexo'];
    $tipoDocumento_idTipoDocumento = $_POST['tipoDocumento_idTipoDocumento'];
    $valorDocumento = $_POST['valorDocumento'];
    $tipoContacto_idTipoContacto = $_POST['tipoContacto_idTipoContacto'];
    $valorContacto = $_POST['valorContacto'];

    $persona = new Persona();
    $persona->setNombrePersona($nombrePersona);
    $persona->setApellidoPersona($apellidoPersona);
    $persona->setEdadPersona($edadPersona);
    $persona->setTipoSexo_idTipoSexo($tipoSexo_idTipoSexo);

    if ($persona->crearPersona()) {
        $idPersona = $persona->getLastInsertedId();

        $personaDocumento = new PersonaDocumento();
        $personaDocumento->setPersona_idPersona($idPersona);
        $personaDocumento->setTipoDocumento_idTipoDocumento($tipoDocumento_idTipoDocumento);
        $personaDocumento->setValor($valorDocumento);

        if ($personaDocumento->crearPersonaDocumento()) {
            $personaContacto = new PersonaContacto();
            $personaContacto->setPersona_idPersona($idPersona);
            $personaContacto->setTipoContacto_idTipoContacto($tipoContacto_idTipoContacto);
            $personaContacto->setValor($valorContacto);

            if ($personaContacto->crearPersonaContacto()) {
                header('Location: ../view/gestionPerfil.php');
                exit();
            } else {
                header('Location: ../view/gestionPerfil.php?error=Error al crear el contacto de la persona');
                exit();
            }
        } else {
            header('Location: ../view/gestionPerfil.php?error=Error al crear el documento de la persona');
            exit();
        }
    } else {
        header('Location: ../view/gestionPerfil.php?error=Error al registrar la persona');
        exit();
    }
}
?>
