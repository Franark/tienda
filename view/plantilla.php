<?php
ob_start();
session_start();

function loadPage($page) {
    require_once('model/rolPermiso.php');
    $rolPermiso = new RolPermiso();

    $publicPages = ['signup', 'login', 'enviarMensaje', 'olvidoPassword', 'reestablecerContraseña', 'verificarStock', 'catalogoProducto', 'verProduct'];

    if (isset($_SESSION['idUsuario'])) {
        if (in_array($page, $publicPages)) {
            if (isset($_SESSION['idRolUsuario'])) {
                switch ($_SESSION['idRolUsuario']) {
                    case 1: 
                        header('Location: ./?page=gestionUsuarios');
                        break;
                    case 2: 
                        header('Location: ./?page=catalogoProductos');
                        break;
                    case 3: 
                        header('Location: ./?page=gestionProductos');
                        break;
                    case 4: 
                        header('Location: ./?page=gestionEnvios');
                        break;
                    default:
                        header('Location: ./?page=login&error=Rol desconocido');
                        break;
                }
            } else {
                header('Location: ./?page=login&error=No se ha detectado un rol');
            }
            exit;
        }
    }    

    $privatePages = isset($_SESSION['idRolUsuario']) ? $rolPermiso->listarRolPermisos($_SESSION['idRolUsuario']) : [];

    if (in_array($page, $publicPages)) {
        include('view/' . $page . '.php');
    } elseif (isset($_SESSION['nickname']) && in_array($page, $privatePages)) {
        include('view/' . $page . '.php');
    } else {
        include('view/errors/403.php');
    }
}

function mostrarSidebarPorRol($idRolUsuario) {
    if ($idRolUsuario == 1) {
        include('components/sidebarAdmin.php');
    } elseif ($idRolUsuario == 2) {
        include('components/sidebarCliente.php');
    } elseif ($idRolUsuario == 3) {
        include('components/sidebarEmpleado.php');
    }elseif ($idRolUsuario == 4) {
        include('components/sidebarRepartidor.php');
    }
    else {
        include('components/sidebarInvitado.php');
    }
}

function mostrarNotificaciones($idRolUsuario) {
    if ($idRolUsuario == 1) {
        include('components/notificacionAdmin.php');
    } elseif ($idRolUsuario == 3) {
        include('components/notificacionAdmin.php');
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página de Inicio</title>
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/sweetalert2/sweetalert2.min.css">
    <script src="assets/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/javascript/jquery-3.7.1.min.js.js"></script>
</head>
<body>
<button class="sidebar-toggle" onclick="toggleSidebar()">☰</button>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <img src="assets/images/icons8-user-24.png" alt="User Icon" class="user-icon" onclick="location.href='./?page=perfil';">
        <h4><?php echo isset($_SESSION['nickname']) ? $_SESSION['nickname'] : 'Invitado'; ?></h4>
        <p>
            <?php 
            if (isset($_SESSION['idRolUsuario'])) {
                foreach ($_SESSION['nombreRol'] as $rol) {
                    if ($rol['idRolUsuario'] == $_SESSION['idRolUsuario']) {
                        echo $rol['nombreRol'];
                        break;
                    }
                }
            }
            ?>
        </p>
    </div>
    <div class="notifications-section">
        <?php
        if(isset($_SESSION['idRolUsuario'])){
            mostrarNotificaciones($_SESSION['idRolUsuario']);
        } else {
            mostrarNotificaciones(null);
        }
       ?>
    </div>
    <nav>
        <ul>
            <?php
            if (isset($_SESSION['idRolUsuario'])) {
                mostrarSidebarPorRol($_SESSION['idRolUsuario']);
            } else {
                mostrarSidebarPorRol(null);
            }
            ?>
        </ul>
    </nav>
</div>


<div class="main-content">
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'catalogoProducto';
    loadPage($page);
    ?>
</div>

<script>
function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    var sidebar = document.getElementById('sidebar');
    var toggleButton = document.querySelector('.sidebar-toggle');
    if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        sidebar.classList.remove('active');
    }
});

function toggleNotifications() {
    var notificationsList = document.getElementById('notifications-list');
    notificationsList.style.display = notificationsList.style.display === 'none' ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    var notificationIcon = document.querySelector('.notifications-toggle');
    var notificationsList = document.getElementById('notifications-list');
    var notificationCount = document.querySelector('.notification-count');

    notificationIcon.addEventListener('click', function () {
        notificationsList.style.display =
            notificationsList.style.display === 'none' || notificationsList.style.display === ''
                ? 'block'
                : 'none';

        if (notificationsList.style.display === 'block') {
            notificationCount.classList.add('hidden');

            fetch('controller/marcarNotificacionesLeidas.php', { method: 'POST' })
                .then(response => response.text())
                .then(data => console.log('Notificaciones marcadas como leídas: ', data))
                .catch(error => console.error('Error:', error));
            }
    });
});
</script>
</body>
</html>
<?php
ob_end_flush();
?>