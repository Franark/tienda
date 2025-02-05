<?php
require_once('../model/conexion.php');
require_once('../model/orden.php');

session_start();
$usuarioId = $_SESSION['idUsuario'];

$orden = new Orden();
$totalOrdenes = $orden->contarOrdenesPorUsuario($usuarioId);

echo $totalOrdenes;
?>
