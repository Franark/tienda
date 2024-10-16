<?php
session_start();
require_once('model/producto.php');

if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
    $producto = new Producto();
    foreach ($_SESSION['carrito'] as $productoEnCarrito) {
        $producto->actualizarStock($productoEnCarrito['idProducto'], $productoEnCarrito['cantidad']);
    }
}
$_SESSION = [];
session_destroy();
header('Location: ./?page=login');
exit();
?>
