<?php
require_once('model/producto.php');
$producto = new Producto();

$notificaciones = $producto->obtenerNotificaciones();
$cantidadNotificaciones = array_reduce($notificaciones, function ($count, $n) {
    return !$n['leido'] ? $count + 1 : $count;
}, 0);

$notificacionesVisibles = array_slice($notificaciones, 0, 5);
?>

<div class="notification-icon notifications-toggle">
    <i class="fa fa-bell"></i>
    <span class="notification-text">Notificaciones</span>
    <span class="notification-count <?php echo $cantidadNotificaciones > 0 ? '' : 'hidden'; ?>">
        <?php echo $cantidadNotificaciones; ?>
    </span>
</div>
<ul class="notifications-list" id="notifications-list" style="display:none;">
    <?php foreach ($notificacionesVisibles as $n): ?>
        <li class="<?php echo $n['leido'] ? 'leido' : ''; ?>">
            <?php echo $n['mensaje']; ?>
        </li>
    <?php endforeach; ?>
    <li>
        <a href="./?page=todasLasNotificaciones" class="ver-todas">Ver todas las notificaciones</a>
    </li>
</ul>
