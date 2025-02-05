<?php
require_once 'vendor/autoload.php';
require_once 'model/producto.php';

use Dompdf\Dompdf;

$producto = new Producto();
$reporte = $_GET['reporte'] ?? '';

$dompdf = new Dompdf();

$html = '';

if ($reporte === 'ventas_stock') {
    $ventas = $producto->productosVendidosPorMes();
    $stock = $producto->stockProuductos();

    $stockMap = [];
    foreach ($stock as $s) {
        $stockMap[$s['nombreProducto']] = $s['stock'];
    }

    $html = '
    <style>
        h1 {
            font-size: 24px;
            text-align: center;
        }
        h2 {
            font-size: 20px;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>';

    $html .= '<h1>Reporte de Ventas y Stock</h1>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<thead><tr><th>Producto</th><th>Mes</th><th>Año</th><th>Cantidad Vendida</th><th>Stock Actual</th></tr></thead><tbody>';

    foreach ($ventas as $venta) {
        $mes = $venta['mes'];
        $año = $venta['año'];
        $cantidadVendida = $venta['total_vendido'];
        $stockActual = isset($stockMap[$venta['nombreProducto']]) ? $stockMap[$venta['nombreProducto']] : 'No encontrado';

        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($venta['nombreProducto'] ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($mes ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($año ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($cantidadVendida ?? 'N/A') . '</td>';
        $html .= '<td>' . htmlspecialchars($stockActual) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';

} elseif ($reporte === 'productos_vencer') {
    $productosPorVencer = $producto->productosPorVencer();

    $html .= '
    <style>
        h1 {
            font-size: 24px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>';

    $html .= '<h1>Reporte de Productos por Vencer</h1>';
    $html .= '<table>';
    $html .= '<thead><tr><th>Producto</th><th>Días para Vencimiento</th><th>Fecha de Vencimiento</th></tr></thead><tbody>';

    foreach ($productosPorVencer as $pv) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($pv['nombreProducto'] ?? 'N/A') . '</td>';
        $html .= '<td>' . (isset($pv['diasParaVencer']) && $pv['diasParaVencer'] < 0 ? 'Ya vencido' : htmlspecialchars($pv['diasParaVencer'] ?? 'N/A') . ' días') . '</td>';
        $html .= '<td>' . htmlspecialchars(isset($pv['fechaVencimiento']) ? date('d-m-Y', strtotime($pv['fechaVencimiento'])) : 'N/A') . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
}

else {
    $usuarios = $producto->usuariosConMasCompras();
    $html .= '
    <style>
        h1 {
            font-size: 24px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>';

    $html .= '<h1>Reporte de Clientes con Más Compras</h1>';
    $html .= '<table>';
    $html .= '<thead><tr><th>Cliente</th><th>Total Compras</th></tr></thead><tbody>';

    foreach ($usuarios as $usuario) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($usuario['nombre_cliente']) . '</td>';
        $html .= '<td>' . htmlspecialchars($usuario['total_compras']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</tbody></table>';
}

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('reporte_' . $reporte . '.pdf', ['Attachment' => true]);
?>
