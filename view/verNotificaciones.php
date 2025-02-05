<?php
require_once('model/envio.php');

if (!isset($_SESSION['idUsuario'])) {
    header('Location: ./?page=login');
    exit;
}

$envio = new Envio();
$idUsuario = $_SESSION['idUsuario'];

$notificaciones = $envio->notificacionEstadoPedido($idUsuario); 

$notificacionesPorPagina = 15;
$totalNotificaciones = count($notificaciones);
$totalPaginas = ceil($totalNotificaciones / $notificacionesPorPagina);

$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($paginaActual < 1) {
    $paginaActual = 1;
} elseif ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$inicio = ($paginaActual - 1) * $notificacionesPorPagina;

$notificacionesPagina = array_slice($notificaciones, $inicio, $notificacionesPorPagina);
?>
<h1>Todas las Notificaciones</h1>

<ul>
    <?php
    if (!empty($notificacionesPagina)) {
        foreach ($notificacionesPagina as $notificacion) {
            echo "<li>El estado de su pedido con el número {$notificacion['idOrden']} está: {$notificacion['estado']}</li>";
        }
    } else {
        echo "<li>No hay notificaciones disponibles.</li>";
    }
    ?>
</ul>
<div class="pagination">
    <?php if ($paginaActual > 1): ?>
        <a href="?page=verNotificaciones&pagina=<?php echo $paginaActual - 1; ?>">&laquo; Anterior</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
        <a href="?page=verNotificaciones&pagina=<?php echo $i; ?>" <?php if ($i === $paginaActual) echo 'class="active"'; ?>>
            <?php echo $i; ?>
        </a>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?page=verNotificaciones&pagina=<?php echo $paginaActual + 1; ?>">Siguiente &raquo;</a>
    <?php endif; ?>
</div>

<a href="./?page=catalogoProductos">Volver al catálogo</a>