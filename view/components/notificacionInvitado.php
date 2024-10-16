<div class="notification-icon notifications-toggle">
    <i class="fa fa-bell"></i>
    <span class="notification-text">Notificaciones</span>
    <span class="notification-count">3</span>
</div>
<ul class="notifications-list" id="notifications-list" style="display:none;">
    <?php
    require_once('model/producto.php');

    $producto = new Producto();
    $productosSinStock = $producto->obtenerProductosSinStock();
    
    foreach ($productosSinStock as $p) {
        echo "El producto {$p['nombreProducto']} no tiene stock</br>";
    }
    ?>
</ul>