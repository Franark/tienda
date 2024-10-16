<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/factura.php');
require_once('../model/orden.php');
require_once('../model/cliente.php');
require_once('enviarCorreoFactura.php');  // Archivo que maneja PHPMailer para el envío de correos

function generarFactura($idOrden) {
    // Crear instancia de la clase Orden para obtener los detalles de la orden
    $orden = new Orden();
    $detalleOrden = $orden->obtenerOrdenPorId($idOrden);

    if (!$detalleOrden) {
        echo "<p>No se pudo encontrar la orden.</p>";
        return false;
    }

    // Verificar si existe la clave cliente_idCliente
    if (!isset($detalleOrden['cliente_idCliente'])) {
        echo "<p>No se pudo encontrar el ID del cliente en la orden.</p>";
        return false;
    }

    // Verificar si existe la clave cliente_idCliente
    if (!isset($detalleOrden['cliente_idCliente'])) {
        echo "<p>No se pudo encontrar el ID del cliente en la orden.</p>";
        return false;
    }

    
    // Calcular el monto total de la orden
    $montoTotal = 0;
    foreach ($detalleOrden['productos'] as $producto) {
        $montoTotal += $producto['precioTotal'];
    }

    // Crear una nueva instancia de Factura
    $factura = new Factura();
    $factura->setFechaEmision(date("Y-m-d"));
    $factura->setMontoTotal($montoTotal);
    $factura->setOrdenIdOrden($idOrden);

    // Insertar la factura en la base de datos
    if ($factura->crearFactura()) {
        $cliente = new Cliente();
        $clienteData = $cliente->obtenerClientePorId($detalleOrden['cliente_idCliente']); // Esto ahora retorna un array

        // Verifica si se recuperaron los datos del cliente
        if (!$clienteData || empty($clienteData['correoElectronico'])) {
            echo "<p>No se pudo encontrar el correo electrónico del cliente.</p>";
            return false;
        }

        // Preparar los detalles del correo
        $asunto = "Factura de tu compra - Orden #" . $idOrden;
        $mensaje = generarContenidoFactura($factura, $clienteData, $detalleOrden['productos']);
        $emailCliente = $clienteData['correoElectronico']; // Acceso directo al array

        // Enviar el correo al cliente
        if (enviarCorreoFactura($emailCliente, $asunto, $mensaje)) {
            echo "<p>La factura ha sido generada y enviada al correo del cliente.</p>";
            return true;
        } else {
            echo "<p>Hubo un problema al enviar el correo.</p>";
            return false;
        }
    } else {
        echo "<p>No se pudo generar la factura.</p>";
        return false;
    }
}

// Función para generar el contenido del correo de la factura
function generarContenidoFactura($factura, $cliente, $productos) {
    $contenido = "<h1>Factura #" . $factura->getIdFactura() . "</h1>";
    $contenido .= "<p>Fecha de emisión: " . $factura->getFechaEmision() . "</p>";
    // Acceso a los datos del cliente desde el array
    $contenido .= "<p>Cliente: " . $cliente['nombre'] . " " . $cliente['apellido'] . "</p>"; 
    $contenido .= "<h2>Detalles de la compra:</h2>";
    $contenido .= "<ul>";
    foreach ($productos as $producto) {
        $contenido .= "<li>" . $producto['nombreProducto'] . " - Cantidad: " . $producto['cantidad'] . " - Precio total: $" . number_format($producto['precioTotal'], 2, ',', '.') . "</li>";
    }
    $contenido .= "</ul>";
    $contenido .= "<h3>Monto total: $" . number_format($factura->getMontoTotal(), 2, ',', '.') . "</h3>";

    return $contenido;
}
?>
