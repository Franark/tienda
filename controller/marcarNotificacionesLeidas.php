<?php
require_once(__DIR__ . '/../model/conexion.php');
require_once(__DIR__ . '/../model/producto.php');

$conexion = new Conexion();
$conn = $conexion->conectar();

if (!$conn) {
    die("Error al conectar con la base de datos.");
}

$query = "UPDATE notificaciones SET leido = 1 WHERE leido = 0";

if ($conn->query($query) === TRUE) {
    echo "Notificaciones actualizadas";
} else {
    echo "Error al actualizar las notificaciones: " . $conn->error;
}

$conexion->desconectar();

