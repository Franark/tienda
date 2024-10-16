<?php
require_once('../model/envio.php');

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
        $envios = $envio->listarEnviosEnProceso($enviosPorPagina, $offset);
        break;
    default:
        $envios = [];
}

$html = '';
foreach ($envios as $env) {
    $html .= "<tr>
                  <td>{$env['idEnvio']}</td>
                  <td>{$env['cliente']}</td>
                  <td>{$env['fecha']}</td>
                  <td>{$env['estado']}</td>
                  <td><button onclick='cancelarEnvio({$env['idEnvio']})'>Cancelar</button></td>
              </tr>";
}

echo $html;
?>
