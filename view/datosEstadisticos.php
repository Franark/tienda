<?php
require_once('model/producto.php');

$producto = new Producto();

$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

$ventas = $producto->productosVendidosPorMes($fecha_inicio, $fecha_fin);
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

$usuarios = $producto->usuariosConMasCompras($fecha_inicio, $fecha_fin);
?>
<div>
    <h2>Seleccionar rango de fechas</h2>
    <form method="get" action="">
        <input type="hidden" name="page" value="datosEstadisticos">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input 
            type="date" 
            name="fecha_inicio" 
            id="fecha_inicio" 
            value="<?= htmlspecialchars($fecha_inicio) ?>" 
            required>
        
        <label for="fecha_fin">Fecha de fin:</label>
        <input 
            type="date" 
            name="fecha_fin" 
            id="fecha_fin" 
            value="<?= htmlspecialchars($fecha_fin) ?>" 
            required>
        
        <button type="submit">Ver Productos Vendidos</button>
    </form>
</div>

<div>
    <h2>Productos Vendidos por Mes</h2>
    <canvas id="ventasChart"></canvas>
</div>

<div>
    <h2>Stock de Productos</h2>
    <canvas id="stockChart"></canvas>
</div>

<div>
    <h2>Usuarios con más compras</h2>
    <canvas id="usuariosChart"></canvas>
</div>

<div>
    <button id="descargarPDF">Descargar Gráficos en PDF</button>
</div>





<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

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
    const ctxUsuarios = document.getElementById('usuariosChart').getContext('2d');
    const etiquetasUsuarios = <?= json_encode(array_column($usuarios, 'nombre_cliente')) ?>;
    const datosUsuarios = <?= json_encode(array_column($usuarios, 'total_compras')) ?>;

    new Chart(ctxUsuarios, {
        type: 'bar',
        data: {
            labels: etiquetasUsuarios,
            datasets: [{
                label: 'Total de Compras por Usuario',
                data: datosUsuarios,
                borderWidth: 1,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
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

    document.getElementById('descargarPDF').addEventListener('click', async () => {
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF();

        const graficos = [
            { id: 'ventasChart', titulo: 'Productos Vendidos por Mes' },
            { id: 'stockChart', titulo: 'Stock de Productos' },
            { id: 'usuariosChart', titulo: 'Usuarios con más Compras' },
        ];

        let yPosition = 10;

        for (const grafico of graficos) {
            const canvas = document.getElementById(grafico.id);
            const imgData = await html2canvas(canvas).then(canvas => canvas.toDataURL('image/png'));

            pdf.setFontSize(16);
            pdf.text(grafico.titulo, 10, yPosition);
            yPosition += 10;

            const imgWidth = 190;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
            yPosition += imgHeight + 10;

            if (yPosition > 100) {
                pdf.addPage();
                yPosition = 10;
            }
        }
        
        pdf.save('datosEstadisticos_' + new Date().toLocaleDateString() + '.pdf');
    });


</script>
