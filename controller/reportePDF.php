<?php
require 'vendor/autoload.php';
require_once('model/producto.php');

use Dompdf\Dompdf;

$producto = new Producto();

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

$ventas = $producto->productosVendidosPorMes($fecha_inicio, $fecha_fin);
$stock = $producto->stockProuductos();
$usuarios = $producto->usuariosConMasCompras($fecha_inicio, $fecha_fin);

$html = '<h1>Reporte Estadístico</h1>';
$html .= '<h2>Rango de Fechas: ' . htmlspecialchars($fecha_inicio) . ' a ' . htmlspecialchars($fecha_fin) . '</h2>';

$html .= '<h3>Productos Vendidos por Mes</h3>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Producto</th>
                <th>Mes-Año</th>
                <th>Total Vendido</th>
            </tr>';
foreach ($ventas as $v) {
    $html .= '<tr>
                <td>' . htmlspecialchars($v['nombreProducto']) . '</td>
                <td>' . htmlspecialchars($v['mes'] . '-' . $v['año']) . '</td>
                <td>' . htmlspecialchars($v['total_vendido']) . '</td>
              </tr>';
}
$html .= '</table>';

$html .= '<h3>Stock de Productos</h3>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Producto</th>
                <th>Stock</th>
            </tr>';
foreach ($stock as $s) {
    $html .= '<tr>
                <td>' . htmlspecialchars($s['nombreProducto']) . '</td>
                <td>' . htmlspecialchars($s['stock']) . '</td>
              </tr>';
}
$html .= '</table>';

$html .= '<h3>Usuarios con Más Compras</h3>';
$html .= '<table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>Usuario</th>
                <th>Total Compras</th>
            </tr>';
foreach ($usuarios as $u) {
    $html .= '<tr>
                <td>' . htmlspecialchars($u['nombre_cliente']) . '</td>
                <td>' . htmlspecialchars($u['total_compras']) . '</td>
              </tr>';
}
$html .= '</table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream('reporte_estadistico.pdf', ['Attachment' => true]);
