<?php
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;

require_once('model/producto.php');
$producto = new Producto();
$producto->page_size = $page_size;
$producto->current_page = $current_page;

$total_notificaciones = $producto->contarNotificaciones();

$total_pages = ceil($total_notificaciones / $page_size);

$notificaciones = $producto->listarNotificacionesProductos($current_page, $page_size);
?>

<h1>Todas las Notificaciones</h1>
<ul class="all-notifications-list">
    <?php foreach ($notificaciones as $n): ?>
        <li class="<?php echo $n['leido'] ? 'leido' : ''; ?>">
            <?php echo $n['mensaje']; ?> - <small><?php echo $n['fechaCreacion']; ?></small>
        </li>
    <?php endforeach; ?>
</ul>
<a href="./?page=gestionProductos" class="btn-volver">Volver al inicio</a>

<nav class="pagination">
    <ul>
        <li><a href="?page=todasLasNotificaciones&current_page=<?= max(1, $current_page - 1) ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>Atrás</a></li>
        
        <p>Página <?= $current_page ?> de <?= $total_pages ?></p>

        <li><a href="?page=todasLasNotificaciones&current_page=<?= min($current_page + 1, $total_pages) ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Siguiente</a></li>
    </ul>
</nav>