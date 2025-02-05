<?php
require_once('/var/www/html/tienda/model/producto.php');
$producto = new Producto();

$productosSinStock = $producto->obtenerProductosSinStock();
foreach ($productosSinStock as $p) {
    $mensaje = "El producto {$p['nombreProducto']} no tiene stock.";
    $producto->insertarNotificacion($p['idProducto'], $mensaje, 'Stock');
}

$productosPorVencer = $producto->productosPorVencer();
foreach ($productosPorVencer as $p) {
    $mensaje = "El producto {$p['nombreProducto']} expira en {$p['diasParaVencimiento']} días.";
    $producto->insertarNotificacion($p['idProducto'], $mensaje, 'Vencimiento');
}

?>