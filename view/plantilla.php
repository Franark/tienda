<?php
session_start();

function includeNavbar($role) {
    switch ($role) {
        case 'Administrador':
            include('components/adminNavbar.php');
            break;
        case 'Empleado':
            include('components/empleadoNavbar.php');
            break;
        case 'Cliente':
            include('components/clienteNavbar.php');
            break;
        default:
            include('components/defaultNavbar.php');
            break;
    }
}

function loadPage($page) {
    require_once('model/rolPermiso.php');
    $rolPermiso = new RolPermiso();

    $publicPages = ['signup', 'login'];
    $privatePages = $rolPermiso->listarRolPermisos($_SESSION['idRolUsuario']); // Array con los permisos del usuario

    if (in_array($page, $publicPages)) {
        include('view/' . $page . '.php');
    } elseif (isset($_SESSION['nickname']) && in_array($page, $privatePages)) {
        include('view/' . $page . '.php');
    } else {
        include('view/errors/403.php');
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
    <script src="assets/javascript/jquery-3.7.1.min.js.js"></script>
</head>
<body>

<?php
$role = isset($_SESSION['nombreRol']) ? $_SESSION['nombreRol'] : null;
includeNavbar($role);
?>

<div class="sidebar">
    <h2>Gestión de usuarios</h2>
    <nav>
        <ul>
            <li><a href="./?page=gestionCategorias">Gestionar Categorías</a></li>
            <li><a href="./?page=gestionTipoDocumento">Gestionar Tipo de Documentos</a></li>
            <li><a href="./?page=gestionMarcas">Gestionar Marcas</a></li>
            <li><a href="./?page=gestionProductos">Gestionar Productos</a></li>
            <li><a href="./?page=gestionTipoContacto">Gestionar Tipos de Contacto</a></li>
            <li><a href="./?page=gestionUsuarios">Gestionar Usuarios</a></li>
            <li><a href="./?page=gestionBarrio">Gestionar Barrios</a></li>
            <li><a href="./?page=gestionPaginas">Gestionar Paginas</a></li>
            <li><a href="./?page=perfil">Perfil</a></li>

        </ul>
    </nav>
</div>

<div class="main-content">
    <?php
    $page = isset($_GET['page']) ? $_GET['page'] : 'login';
    loadPage($page);
    ?>
</div>

<script>
    document.getElementById('user-icon').addEventListener('click', function() {
        var dropdown = document.getElementById('user-dropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function(event) {
        var dropdown = document.getElementById('user-dropdown');
        var userIcon = document.getElementById('user-icon');
        if (!userIcon.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
</script>
</body>
</html>
