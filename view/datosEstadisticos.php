<div>
  <h2>Productos Vendidos por Mes</h2>
  <canvas id="ventasChart"></canvas>
</div>

<div>
  <h2>Stock de Productos</h2>
  <canvas id="stockChart"></canvas>
</div>

<?php
require_once('model/producto.php');

$producto = new Producto();

$ventas = $producto->productosVendidosPorMes();
$etiquetasVentas = [];
$datosVentas = [];

foreach ($ventas as $v) {
    $etiquetasVentas[] = $v['nombreProducto'] . ' (' . $v['mes'] . '-' . $v['año'] . ')';
    $datosVentas[] = $v['total_vendido'];
}

$etiquetasVentas_json = json_encode($etiquetasVentas);
$datosVentas_json = json_encode($datosVentas);

$stock = $producto->stockProuductos();
$etiquetasStock = [];
$datosStock = [];

foreach ($stock as $s) {
    $etiquetasStock[] = $s['nombreProducto'];
    $datosStock[] = $s['stock'];
}

$etiquetasStock_json = json_encode($etiquetasStock);
$datosStock_json = json_encode($datosStock);
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctxVentas = document.getElementById('ventasChart').getContext('2d');
    const etiquetasVentas = <?= $etiquetasVentas_json ?>;
    const datosVentas = <?= $datosVentas_json ?>;
    
    new Chart(ctxVentas, {
        type: 'bar',
        data: {
            labels: etiquetasVentas,
            datasets: [{
                label: 'Productos vendidos por mes',
                data: datosVentas,
                borderWidth: 1,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true
                }
            }
        }
    });

    const ctxStock = document.getElementById('stockChart').getContext('2d');
    const etiquetasStock = <?= $etiquetasStock_json ?>;
    const datosStock = <?= $datosStock_json ?>;
    
    new Chart(ctxStock, {
        type: 'bar',
        data: {
            labels: etiquetasStock,
            datasets: [{
                label: 'Stock de productos',
                data: datosStock,
                borderWidth: 1,
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
