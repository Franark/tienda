<?php
require_once('model/producto.php');

$producto = new Producto();
$ventas = $producto->productosVendidosPorMes();
$stock = $producto->stockProuductos();
$productosPorVencer = $producto->productosPorVencer();

$stockMap = [];
foreach ($stock as $s) {
    $stockMap[$s['nombreProducto']] = $s['stock'];
}
?>

<h1>Reporte de Productos</h1>

<h2>Ventas y Stock</h2>
<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Mes</th>
            <th>Año</th>
            <th>Cantidad Vendida</th>
            <th>Stock Actual</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($ventas as $venta): ?>
            <tr>
                <td><?php echo htmlspecialchars($venta['nombreProducto'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($venta['mes'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($venta['año'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($venta['total_vendido'] ?? 'N/A'); ?></td>
                <td>
                    <?php
                    $stockActual = $stockMap[$venta['nombreProducto']] ?? 'No definido';
                    echo htmlspecialchars($stockActual);
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="?page=generarPDF&reporte=ventas_stock" target="_blank">
    <button>Descargar Reporte de Ventas y Stock</button>
</a>

<h2>Productos por Vencer</h2>
<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Días para Vencimiento</th>
            <th>Fecha de Vencimiento</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($productosPorVencer as $pv): ?>
            <tr>
                <td><?php echo htmlspecialchars($pv['nombreProducto'] ?? 'N/A'); ?></td>
                <td>
                    <?php 
                        if (isset($pv['diasParaVencer']) && $pv['diasParaVencer'] < 0) {
                            echo "Ya vencido";
                        } elseif (isset($pv['diasParaVencer'])) {
                            echo htmlspecialchars($pv['diasParaVencer']) . ' días';
                        } else {
                            echo 'N/A';
                        }
                    ?>
                </td>
                <td>
                    <?php 
                        echo isset($pv['fechaVencimiento']) ? htmlspecialchars(date('d-m-Y', strtotime($pv['fechaVencimiento']))) : 'N/A';
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="?page=generarPDF&reporte=productos_vencer" target="_blank">
    <button>Descargar Reporte de Productos por Vencer</button>
</a>
