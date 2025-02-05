<?php
require_once('../model/conexion.php');
require_once('../model/orden.php');

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'pendientes';

$orden = new Orden();

switch ($tabla) {
    case 'pendientes':
        $totalOrdenes = $orden->contarOrdenesPendientes();
        break;
    case 'enProceso':
        $totalOrdenes = $orden->contarOrdenesEnProceso();
        break;
    case 'entregados':
        $totalOrdenes = $orden->contarOrdenesEntregadas();
        break;
    default:
        $totalOrdenes = 0;
        break;
}

echo $totalOrdenes;
