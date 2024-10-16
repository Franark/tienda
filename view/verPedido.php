<?php
require_once('model/envio.php');
require_once('model/orden.php');

if (isset($_GET['idEnvio'])) {
    $idEnvio = $_GET['idEnvio'];

    $envio = new Envio();
    $detalleEnvio = $envio->obtenerDetalleEnvio($idEnvio);
    if ($detalleEnvio) {
        echo "<h2>Detalles del Pedido</h2>";
        echo "<p>Número de Envío: {$detalleEnvio['idEnvio']}</p>";
        echo "<p>Cliente: {$detalleEnvio['nickname']}</p>";
        echo "<p>Fecha: {$detalleEnvio['fechaEnvio']}</p>";
        echo "<p>Productos:</p>";
        echo "<ul>";

        foreach ($detalleEnvio['productos'] as $producto) {
            echo "<li>{$producto['nombreProducto']} - Cantidad: {$producto['cantidad']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No se encontraron detalles para este envío.</p>";
    }
} else {
    echo "<p>ID de envío no proporcionado.</p>";
}
?>
