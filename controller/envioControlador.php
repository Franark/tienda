<?php
require_once('../model/envio.php');
session_start();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accion = $_POST['accion'] ?? null;
        $idEnvio = intval($_POST['idEnvio'] ?? 0);
        $repartidorId = $_SESSION['idUsuario'] ?? null;

        if (!$accion || !$idEnvio || ($accion !== 'cancelarEnvio' && !$repartidorId)) {
            throw new Exception('Faltan datos en la solicitud.');
        }

        $envio = new Envio();
        $envio->setIdEnvio($idEnvio);

        switch ($accion) {
            case 'entregarEnvio':
                if ($envio->cambiarEstadoEnvio('Entregado', $repartidorId)) {
                    echo json_encode(['status' => 'success', 'message' => 'El envío ha sido entregado correctamente.']);
                } else {
                    throw new Exception('No se pudo entregar el envío.');
                }
                break;

            case 'iniciarEnvio':
                if ($envio->cambiarEstadoEnvio('En Proceso', $repartidorId)) {
                    echo json_encode(['status' => 'success', 'message' => 'El envío se ha iniciado correctamente.']);
                } else {
                    throw new Exception('No se pudo iniciar el envío.');
                }
                break;

            case 'cancelarEnvio':
                if ($envio->cancelarEnvio($idEnvio)) {
                    echo json_encode(['status' => 'success', 'message' => 'El envío ha sido cancelado correctamente.']);
                } else {
                    throw new Exception('No se pudo cancelar el envío.');
                }
                break;

            default:
                throw new Exception('Acción no válida.');
        }
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
