<?php
session_start();
require_once('../model/orden.php');
require_once('generarFactura.php');

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
    exit();
}

if (!isset($_POST['total'], $_POST['direccion'])) {
    echo "<p>Hubo un problema con el proceso de pago.</p>";
    exit();
}

if (!isset($_SESSION['cliente_id'])) {
    echo "<p>Debes estar autenticado para realizar una compra.</p>";
    exit();
}

if (!isset($_POST['total'], $_POST['direccion'], $_POST['metodo_pago'])) {
    echo "<p>Hubo un problema con el proceso de pago.</p>";
    exit();
}

$total = $_POST['total'];
$idDomicilio = $_POST['direccion'];
$carrito = $_SESSION['carrito'];
$cliente_id = $_SESSION['cliente_id'];
$estado = 'Pendiente';
$metodo_pago = $_POST['metodo_pago'];

$orden = new Orden();
$orden->setFechaOrden(date("Y-m-d H:i:s"));
$orden->setClienteIdCliente($cliente_id);
$orden->setEstado($estado);

$productos = [];
foreach ($carrito as $item) {
    $productos[] = [
        'idProducto' => $item['idProducto'],
        'cantidad' => $item['cantidad'],
        'precioTotal' => $item['precio'] * $item['cantidad']
    ];
}

if ($orden->crearOrden($productos, $idDomicilio, $metodo_pago)) {
    if (procesarPago($orden->getIdOrden(), $total)) {
        generarFactura($orden->getIdOrden());

        unset($_SESSION['carrito']);
        
        $mensaje = "";
        if ($metodo_pago === "Efectivo") {
            $mensaje = "¡Compra exitosa! Recuerde que el monto de envío puede variar según su dirección.";
        } elseif ($metodo_pago === "Mercado Pago") {
            $mensaje = "Para que su compra se ponga en proceso, por favor transfiera el monto de su pedido al alias: francisco-ibarra o al CVU 000000000031000066238053292.";
        }

        header("Location: ../?page=ordenesClientes&mensaje=" . urlencode($mensaje));
        exit();
    } else {
        echo "<p>Hubo un problema al procesar el pago. Por favor, inténtalo de nuevo.</p>";
        echo "<a href='../index.php'>Volver a la tienda</a>";
    }
} else {
    echo "<p>Hubo un problema al procesar la compra. Por favor, inténtalo de nuevo.</p>";
    echo "<a href='../index.php'>Volver a la tienda</a>";
}

function procesarPago($idOrden, $total) {
    return true;
}
