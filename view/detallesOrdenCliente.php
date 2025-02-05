<?php
require_once('model/conexion.php');
require_once('model/orden.php');

$idUsuario = $_SESSION['idUsuario'];
$idOrden = isset($_GET['idOrden']) ? (int)$_GET['idOrden'] : 0;

$orden = new Orden();
$detalle = $orden->obtenerDetalleOrdenCliente($idUsuario, $idOrden);

if (empty($detalle) || $detalle[0]['producto'] === null) {
    echo "<p>No se encontraron productos asociados a esta orden.</p>";
    exit;
}

$precioTotalOrden = 0;
foreach ($detalle as $item) {
    $precioTotalOrden += $item['precioProducto'];
}

echo "<h1>Detalle de la Orden #{$idOrden}</h1>";
echo "<p><strong>Fecha de Orden:</strong> {$detalle[0]['fechaOrden']}</p>";
echo "<p><strong>Estado:</strong> {$detalle[0]['estado']}</p>";

echo "<p><strong>Productos:</strong></p>";
echo "<ul>";
foreach ($detalle as $item) {
    echo "<li>{$item['producto']} - Cantidad: {$item['cantidadProducto']} - Precio: {$item['precioProducto']}</li>";
}
echo "</ul>";

echo "<p><strong>Precio total a pagar:</strong> {$precioTotalOrden}</p>";

if (isset($_GET['status'])) {
    $status = $_GET['status'];
    $message = isset($_GET['message']) ? $_GET['message'] : '';

    if ($status === 'success') {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Operación exitosa',
                    text: '{$message}',
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: '{$message}',
                });
              </script>";
    }
}

if (!empty($detalle[0]['reciboPago'])) {
    $reciboPath = 'assets/recibos/' . basename($detalle[0]['reciboPago']);
    
    if (file_exists($reciboPath)) {
        echo "<p><strong>Recibo de pago subido:</strong></p>";
        echo "<img src='{$reciboPath}' alt='Recibo de Pago' style='max-width: 50%; height: auto;'>";
    } else {
        echo "<p>No se pudo cargar el recibo de pago.</p>";
    }
}

echo '<form action="controller/subirRecibo.php" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="idOrden" value="' . $idOrden . '">';
echo '<label for="reciboPago">Subir Recibo de Pago:</label>';
echo '<input type="file" name="reciboPago" id="reciboPago" accept=".jpg,.png,.pdf" required>';
echo '<button type="submit">Subir Recibo</button>';
echo '</form>';
?>