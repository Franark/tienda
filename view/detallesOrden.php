<?php
require_once('model/conexion.php');
require_once('model/orden.php');

$idOrden = isset($_GET['idOrden']) ? (int)$_GET['idOrden'] : 0;
$orden = new Orden();
$detalle = $orden->obtenerDetalleOrden($idOrden);

if (empty($detalle) || $detalle[0]['producto'] === null) {
    echo "<p>No se encontraron productos asociados a esta orden.</p>";
    exit;
}

$precioTotalOrden = 0;
$contactos = [];
foreach ($detalle as $item) {
    $precioTotalOrden += $item['precioProducto'];

    if (!empty($item['contactoValor']) && !empty($item['tipoContacto'])) {
        $contactos[$item['tipoContacto']][] = $item['contactoValor'];
    }
}

echo "<h1>Detalle de la Orden #{$idOrden}</h1>";

$usuario = $detalle[0]['usuario'];
echo "<p><strong>Usuario:</strong> {$usuario}</p>";

$nombrePersona = $detalle[0]['nombrePersona'];
$apellidoPersona = $detalle[0]['apellidoPersona'];
echo "<p><strong>Nombre:</strong> {$nombrePersona} {$apellidoPersona}</p>";

$metodoPago = $detalle[0]['metodoPago'];
echo "<p><strong>Método de Pago:</strong> {$metodoPago}</p>";

$direccion = "{$detalle[0]['barrio']}, Casa: {$detalle[0]['numeroCasa']}";
if (!empty($detalle[0]['piso'])) {
    $direccion .= ", Piso: {$detalle[0]['piso']}";
}
if (!empty($detalle[0]['descripcionDomicilio'])) {
    $direccion .= " ({$detalle[0]['descripcionDomicilio']})";
}
echo "<p><strong>Dirección de entrega:</strong> {$direccion}</p>";

echo "<p><strong>Contactos:</strong></p>";
if (!empty($contactos)) {
    echo "<ul>";
    foreach ($contactos as $tipo => $valores) {
        echo "<li>{$tipo}: " . implode(", ", $valores) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No se encontraron contactos asociados.</p>";
}

echo "<p><strong>Productos:</strong></p>";
echo "<ul>";
foreach ($detalle as $item) {
    echo "<li>{$item['producto']} - Cantidad: {$item['cantidadProducto']} - Precio: {$item['precioProducto']}</li>";
}
echo "</ul>";

echo "<p><strong>Precio total a pagar:</strong> {$precioTotalOrden}</p>";

if (!empty($detalle[0]['reciboPago'])) {
    $reciboPath = 'assets/recibos/' . basename($detalle[0]['reciboPago']);
    
    if (file_exists($reciboPath)) {
        echo "<p><strong>Recibo de pago subido:</strong></p>";
        echo "<img src='{$reciboPath}' alt='Recibo de Pago' style='max-width: 25%; height: auto;'>";
    } else {
        echo "<p>Sin subir todavia.</p>";
    }
}