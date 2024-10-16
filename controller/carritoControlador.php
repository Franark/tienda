<?php
session_start();
require_once('../model/producto.php');

if (isset($_POST['index'])) {
    $index = $_POST['index'];
    $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
    if ($accion === 'actualizar') {
        $nuevaCantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;
        
        if ($nuevaCantidad > 0) {
            $productoCarrito = $_SESSION['carrito'][$index];
            $idProducto = $productoCarrito['idProducto'];
            $cantidadActual = $productoCarrito['cantidad'];
            $producto = new Producto();
            $productoData = $producto->obtenerProductoPorId($idProducto);
            $stockDisponible = $productoData['stock'];
            if ($stockDisponible + $cantidadActual >= $nuevaCantidad) {
                $producto->actualizarStock($idProducto, $cantidadActual - $nuevaCantidad);
                $_SESSION['carrito'][$index]['cantidad'] = $nuevaCantidad;
            } else {
                echo "No hay suficiente stock disponible para actualizar la cantidad.";
            }
        }

    } elseif ($accion === 'eliminar') {
        $productoCarrito = $_SESSION['carrito'][$index];
        $idProducto = $productoCarrito['idProducto'];
        $cantidad = $productoCarrito['cantidad'];
        $producto = new Producto();
        $producto->actualizarStock($idProducto, $cantidad);
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
    header("Location: ../?page=carrito");
    exit();
}
?>
