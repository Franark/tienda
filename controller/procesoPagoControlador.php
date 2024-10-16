<?php
session_start();
require_once('../model/orden.php');
require_once('generarFactura.php');

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
    exit();
}

if (!isset($_POST['total'])) {
    echo "<p>Hubo un problema con el proceso de pago.</p>";
    exit();
}

if (!isset($_SESSION['cliente_id'])) {
    echo "<p>Debes estar autenticado para realizar una compra.</p>";
    exit();
}

$total = $_POST['total'];
$carrito = $_SESSION['carrito'];
$cliente_id = $_SESSION['cliente_id'];
$estado = 'Pendiente';

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

if ($orden->crearOrden($productos)) {
    if (procesarPago($orden->getIdOrden(), $total)) {
        generarFactura($orden->getIdOrden());

        unset($_SESSION['carrito']);
        
        echo "<p>¡Gracias por tu compra! El total es $" . number_format($total, 2, ',', '.') . ".</p>";
        echo "<a href='../index.php'>Volver a la tienda</a>";
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
?>