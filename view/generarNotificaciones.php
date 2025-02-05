<?php
require_once('model/conexion.php');
require_once('model/notificacion.php');

$notificacionModel = new Notificacion();

$usuariosResult = obtenerUsuarios();

while ($usuario = $usuariosResult->fetch_assoc()) {
    $mensaje = "¡Tienes una nueva notificación importante!";
    $notificacionModel->agregarNotificacion($usuario['idUsuario'], $mensaje);
}

function obtenerUsuarios() {
    $conexion = new Conexion();
    $conn = $conexion->conectar();
    $query = "SELECT idUsuario FROM usuario";
    return $conn->query($query);
}

?>
