<div class="notification-icon notifications-toggle">
    <i class="fa fa-bell"></i>
    <span class="notification-text">Notificaciones</span>
    <span class="notification-count">3</span>
</div>

<ul class="notifications-list" id="notifications-list" style="display:none;">
    <?php
    require_once('model/envio.php');
    $envio = new Envio();

    if (isset($_SESSION['idUsuario'])) {
        $idUsuario = $_SESSION['idUsuario'];
        $estadoEnvio = $envio->notificacionEstadoPedido($idUsuario);

        if (!empty($estadoEnvio)) {
            foreach ($estadoEnvio as $e) {
                echo "<li>El estado de su pedido con el número {$e['idOrden']} está: {$e['estado']}</li>";
            }
        } else {
            echo "<li>No tiene notificaciones.</li>";
        }
    } else {
        echo "<li>No hay sesión de usuario activa.</li>";
    }
    ?>
</ul>