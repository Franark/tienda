<?php
require_once('../model/conexion.php');
require_once('../model/envio.php');

$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'pendientes';

$envio = new Envio();

switch ($tabla) {
    case 'pendientes':
        $totalEnvios = $envio->contarEnviosPendientes();
        break;
    case 'enProceso':
        $totalEnvios = $envio->contarEnviosEnProceso();
        break;
    default:
        $totalEnvios = 0;
        break;
}

echo $totalEnvios;
?>
