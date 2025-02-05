<?php
require '../vendor/autoload.php';
require_once('../model/producto.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

$producto = new Producto();

if ($filter === 'sin_stock') {
    $productos = $producto->obtenerProductosSinStock();
} elseif ($filter === 'por_vencer') {
    $productos = $producto->productosPorVencer();
} elseif ($filter) {
    $productos = $producto->listarProductosPorFiltro($filter);
} elseif ($search_query) {
    $productos = $producto->buscarProductos($search_query);
} else {
    $productos = $producto->listarProductos();
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Código de Barra');
$sheet->setCellValue('D1', 'Precio');
$sheet->setCellValue('E1', 'Stock');
$sheet->setCellValue('F1', 'Fecha Vencimiento');
$sheet->setCellValue('G1', 'Marca');
$sheet->setCellValue('H1', 'Categoría');

$row = 2;
foreach ($productos as $p) {
    $sheet->setCellValue("A{$row}", $p['idProducto']);
    $sheet->setCellValue("B{$row}", $p['nombreProducto']);
    $sheet->setCellValue("C{$row}", $p['codigoBarras']);
    $sheet->setCellValue("D{$row}", number_format($p['precio'], 2, '.', ','));
    $sheet->setCellValue("E{$row}", $p['stockActual']);
    $sheet->setCellValue("F{$row}", date('d-m-Y', strtotime($p['fechaVencimiento']))); // Formatear fecha
    $sheet->setCellValue("G{$row}", isset($p['nombreMarca']) ? $p['nombreMarca'] : 'Sin Marca');
    $sheet->setCellValue("H{$row}", isset($p['nombreCategoria']) ? $p['nombreCategoria'] : 'Sin Categoría');
    $row++;
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Listado_Productos.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
