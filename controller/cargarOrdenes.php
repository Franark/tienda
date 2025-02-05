<?php
require_once('../model/conexion.php');
require_once('../model/orden.php');

$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$tabla = isset($_GET['tabla']) ? $_GET['tabla'] : 'pendientes';
$limite = 10;
$offset = ($pagina - 1) * $limite;

$orden = new Orden();

switch ($tabla) {
    case 'pendientes':
        $ordenes = $orden->listarOrdenesPendientes($limite, $offset);
        break;
    case 'enProceso':
        $ordenes = $orden->listarOrdenesEnProceso($limite, $offset);
        break;
    case 'entregados':
        $ordenes = $orden->listarOrdenesEntregadas($limite, $offset);
        break;
    case 'cancelados':
        $ordenes = $orden->listarOrdenesCanceladas($limite, $offset);
        break;
    default:
        $ordenes = [];
        break;
}

foreach ($ordenes as $orden) {
    $fecha = date("d-m-Y H:i:s", strtotime($orden['fechaOrden']));
    
    echo "<tr>";
    echo "<td>" . $orden['idOrden'] . "</td>";
    echo "<td>" . $orden['usuario'] . "</td>";
    echo "<td>" . $fecha . "</td>";
    echo "<td>" . $orden['montoTotal'] . "</td>";
    echo "<td>
        <a href='?page=detallesOrden&idOrden=" . $orden['idOrden'] . "' class='btn btn-info btn-sm'>Ver más</a>
    </td>";


    if ($tabla == 'pendientes') {
        echo "<td>
            <a href='controller/ordenControlador.php?accion=cambiarEstado&estado=En Proceso&idOrden=" . $orden['idOrden'] . "' 
               class='btn btn-primary btn-sm me-2' onclick='mostrarAlertaExito(\"Orden iniciada exitosamente\")'>Iniciar</a>
            <button onclick='cancelarPedido(" . $orden['idOrden'] . ")' class='btn-danger'>Cancelar</button>
        </td>";
    } elseif ($tabla == 'enProceso') {
        echo "<td>
            <button onclick='cancelarPedido(" . $orden['idOrden'] . ")' class='btn btn-danger btn-sm'>Cancelar</button>
        </td>";
    }
    echo "</tr>";
}
?>