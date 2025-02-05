<?php
ini_set('display_errors', 1);
require_once('../model/persona.php');
require_once('../model/direccion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar'])) {
    $idPersona = $_POST['idPersona'] ?? null;
    $nombrePersona = trim($_POST['nombrePersona'] ?? '');
    $apellidoPersona = trim($_POST['apellidoPersona'] ?? '');
    $edadPersona = $_POST['edadPersona'] ?? null;
    $tipoSexo = $_POST['tipoSexo'] ?? null;
    $valorDocumento = $_POST['valorDocumento'] ?? null;
    $tipoDocumento = $_POST['idTipoDocumento'] ?? null;
    $contactos = $_POST['contactos'] ?? []; 


    if (empty($nombrePersona) || empty($apellidoPersona) || empty($edadPersona) || !is_numeric($edadPersona)) {
        $_SESSION['error'] = 'Todos los campos son obligatorios y la edad debe ser un número';
        header('Location: ../?page=perfil');
        exit();
    }

    $persona = new Persona();
    $persona->setIdPersona($idPersona);
    $persona->setNombrePersona($nombrePersona);
    $persona->setApellidoPersona($apellidoPersona);
    $persona->setEdadPersona($edadPersona);
    $persona->setTipoSexo_idTipoSexo($tipoSexo);
    $persona->setTipoDocumento($tipoDocumento);
    $persona->setValorDocumento($valorDocumento);

    $persona->actualizarPersona();

    foreach ($contactos as $contacto) {
        $tipoContacto = $contacto['tipoContacto_idTipoContacto'] ?? null;
        $valor = trim($contacto['valor'] ?? '');
        if (!empty($tipoContacto) && !empty($valor)) {
            if (isset($contacto['id'])) {
                $persona->actualizarContacto($contacto['id'], $tipoContacto, $valor);
            } else {
                $persona->agregarContacto($idPersona, $tipoContacto, $valor);
            }
        }
    }
    

    if (isset($_POST['direcciones']) && is_array($_POST['direcciones'])) {
        $direccionModel = new Direccion();
        
        foreach ($_POST['direcciones'] as $direccion) {
            $idDomicilio = $direccion['idDomicilio'] ?? null;
            $barrio = $direccion['barrio'] ?? '';
            $numeroCasa = $direccion['numeroCasa'] ?? '';
            $piso = $direccion['piso'] ?? '';
            $descripcion = $direccion['descripcion'] ?? '';

            if ($idDomicilio) {
                $direccionModel->actualizarDireccion($idDomicilio, $barrio, $numeroCasa, $piso, $descripcion);
            } else {
                $direccionModel->agregarDireccion($idPersona, $barrio, $numeroCasa, $piso, $descripcion);
            }
        }
    }

    $_SESSION['success'] = 'Perfil actualizado correctamente.';
    header('Location: ../?page=perfil');
    exit();

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'eliminar') {
        $idDomicilio = $data['idDomicilio'] ?? null;

        if ($idDomicilio) {
            $direccionModel = new Direccion();
            $resultado = $direccionModel->eliminarDomicilio($idDomicilio);

            echo json_encode(['success' => $resultado]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de domicilio no válido']);
        }
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($data['accion']) && $data['accion'] === 'validarDatosPerfil') {
    $idUsuario = $_SESSION['idUsuario'];

    $persona = new Persona();
    $datosPersona = $persona->listarPersona($idUsuario);

    $datosCompletos = true;
    $camposFaltantes = [];

    if (empty($datosPersona[0]['nombrePersona'])) {
        $datosCompletos = false;
        $camposFaltantes[] = 'Nombre';
    }

    if (empty($datosPersona[0]['apellidoPersona'])) {
        $datosCompletos = false;
        $camposFaltantes[] = 'Apellido';
    }

    if (empty($datosPersona[0]['edadPersona'])) {
        $datosCompletos = false;
        $camposFaltantes[] = 'Edad';
    }

    if (empty($datosPersona[0]['valorDocumento'])) {
        $datosCompletos = false;
        $camposFaltantes[] = 'Documento';
    }

    if (empty($datosPersona[0]['direcciones'])) {
        $datosCompletos = false;
        $camposFaltantes[] = 'Dirección';
    }

    // Respuesta
    echo json_encode([
        'completo' => $datosCompletos,
        'faltantes' => $camposFaltantes,
    ]);
    exit();
}

?>
