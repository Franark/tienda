
<?php
require_once('model/producto.php');

$producto = new Producto();
$productosSinStock = $producto->obtenerProductosSinStock();

foreach ($productosSinStock as $p) {
    echo "El producto {$p['nombreProducto']} no tiene stock";
}
?>