<?php
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$url = "http://{$_SERVER['HTTP_HOST']}/datosEstadisticos.php?fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin";

$outputFile = 'datosEstadisticos_' . date('Y-m-d') . '.pdf';

$command = "wkhtmltopdf --javascript-delay 7000 '$url' '$outputFile'";
exec($command);
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $outputFile . '"');
readfile($outputFile);
unlink($outputFile);
?>
