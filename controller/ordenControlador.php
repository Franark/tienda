<?php
require_once('../model/orden.php');
require_once('../model/envio.php');

if (isset($_GET['accion'])) {
    $accion = $_GET['accion'];
    $orden = new Orden();
    $envio = new Envio();
    $idOrden = $_GET['idOrden'] ?? (
        isset($_GET['idEnvio']) ? $envio->obtenerOrdenIdPorEnvio($_GET['idEnvio']) : null
    );

    if ($idOrden) {
        $orden->setIdOrden($idOrden);
    }

    switch ($accion) {
        case 'cambiarEstado':
            if (isset($_GET['estado']) && $idOrden) {
                $nuevoEstado = $_GET['estado'];
                if ($orden->cambiarEstado($nuevoEstado)) {
                    manejarEnvio($envio, $idOrden);
                    header("Location: ../?page=gestionEnvios&mensaje=Estado cambiado correctamente");
                } else {
                    header("Location: ../?page=gestionEnvios&error=No se pudo cambiar el estado de la orden.");
                }
            }
            break;

        case 'aceptarOrden':
            if ($idOrden) {
                $idEnvio = $envio->obtenerIdEnvioPorOrden($idOrden);
                if ($idEnvio) {
                    $envio->setIdEnvio($idEnvio);
                    if ($envio->cambiarEstadoEnvio('En entrega')) {
                        header("Location: ../?page=gestionEnvios&mensaje=Has aceptado la orden para delivery.");
                    } else {
                        header("Location: ../?page=gestionEnvios&error=No se pudo aceptar la orden.");
                    }
                } else {
                    header("Location: ../?page=gestionEnvios&error=No se encontró un envío asociado a esta orden.");
                }
            }
            break;

        case 'completarEntrega':
            if ($idOrden) {
                $idEnvio = $envio->obtenerIdEnvioPorOrden($idOrden);
                if ($idEnvio) {
                    $envio->setIdEnvio($idEnvio);
                    if ($envio->cambiarEstadoEnvio('Entregado')) {
                        header("Location: ../?page=gestionEnvios&mensaje=Entrega completada exitosamente.");
                    } else {
                        header("Location: ../?page=gestionEnvios&error=No se pudo completar la entrega.");
                    }
                } else {
                    header("Location: ../?page=gestionEnvios&error=No se encontró un envío asociado a esta orden.");
                }
            }
            break;

        default:
            header("Location: ../?page=gestionOrdenes&error=Acción no válida.");
            break;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'];
    $idOrden = $_POST['idOrden'];

    $orden = new Orden();

    switch ($accion) {
        case 'cancelarOrden':
            $resultado = $orden->actualizarEstadoOrden($idOrden, 'Cancelado');
            echo $resultado ? "OK" : "Error al cancelar el pedido.";
            break;
        case 'cambiarEstado':
            $nuevoEstado = $_GET['estado'];
            $orden->actualizarEstadoOrden($idOrden, $nuevoEstado);
            header("Location: ../cargarOrdenes.php?tabla=pendientes");
            break;
    }
}
function manejarEnvio($envio, $idOrden) {
    $idEnvio = $envio->obtenerIdEnvioPorOrden($idOrden);
    if ($idEnvio) {
        $envio->setIdEnvio($idEnvio);
        $envio->cambiarEstadoEnvio('En proceso'); 
    } else {
        $envio->setOrdenIdOrden($idOrden);
        $envio->setFechaEnvio(date('Y-m-d H:i:s'));
        $envio->setEstadoEnvio('Pendiente'); 
        if (!$envio->crearEnvio()) {
            header("Location: ../?page=gestionEnvios&error=Error al crear un nuevo envío.");
            exit();
        }
    }
}
