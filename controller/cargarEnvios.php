<?php
require_once('../model/envio.php');
session_start();
$repartidorId = $_SESSION['idUsuario'];
$pagina = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'pendientes';
$enviosPorPagina = 4;
$offset = ($pagina - 1) * $enviosPorPagina;

$envio = new Envio();

switch ($tabla) {
    case 'pendientes':
        $envios = $envio->listarEnviosPendientes($enviosPorPagina, $offset);
        break;
    case 'enProceso':
        $envios = $envio->listarEnviosEnProceso($repartidorId, $enviosPorPagina, $offset);
        break;
    case 'entregados':
        $envios = $envio->listarEnviosEntregados($repartidorId, $enviosPorPagina, $offset);
        break;
    case 'cancelados':
        $envios = $envio->listarEnviosCancelados($repartidorId, $enviosPorPagina, $offset);
        break;
    default:
        $envios = [];
}


$html = '';
foreach ($envios as $env) {
    $acciones = '';
    if ($tabla === 'pendientes') {
        $acciones = "<button class='btn btn-primary btn-sm me-2' onclick='iniciarEnvio({$env['idEnvio']})'>Iniciar</button>";
    } elseif ($tabla === 'enProceso') {
        $acciones = "<button class='btn btn-primary btn-sm me-2' onclick='entregarEnvio({$env['idEnvio']})'>Entregar</button>";
        $acciones .= "<button class='btn btn-danger btn-sm' onclick='cancelarEnvio({$env['idEnvio']})'>Cancelar</button>";
    } elseif ($tabla === 'cancelados') {
        $acciones = '';
    }
    $html .= "<tr>
                  <td>{$env['idEnvio']}</td>
                  <td>{$env['nickname']}</td>
                  <td>{$env['fechaEnvio']}</td>
                  <td><a href='?page=detallesOrden&idOrden={$env['idOrden']}' class='btn btn-info btn-sm'>Ver más</a></td>
                  <td>$acciones</td>
              </tr>";
}
echo $html;
