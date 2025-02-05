<?php
require_once('../model/conexion.php');
require_once('../model/producto.php');

if (isset($_POST['accion']) && isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];
    $accion = $_POST['accion'];

    $producto = new Producto();

    if ($accion === 'aceptado') {
        $producto->actualizarStock($idProducto, -$cantidad);
    } else if ($accion === 'cancelado') {
        $producto->actualizarStock($idProducto, $cantidad);
    }

    echo json_encode(["success" => true]);
}
?>
