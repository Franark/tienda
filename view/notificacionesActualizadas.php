<?php
require_once('model/producto.php');
session_start();

$producto = new Producto();
$idUsuario = $_SESSION['idUsuario'];

$notificaciones = $producto->obtenerNotificaciones();
$cantidadSinLeer = array_reduce($notificaciones, function ($count, $n) {
    return !$n['leido'] ? $count + 1 : $count;
}, 0);

$response = [
    'count' => $cantidadSinLeer,
    'notificaciones' => $notificaciones
];

header('Content-Type: application/json');
echo json_encode($response);
?>
