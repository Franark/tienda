<?php
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';
$roleFilter = isset($_GET['roleFilter']) ? $_GET['roleFilter'] : '';

require_once('model/usuario.php');
$usuario = new Usuario();
$usuario->page_size = $page_size;
$usuario->current_page = $current_page;

if ($search_query) {
    if ($roleFilter) {
        $usuarios = $usuario->buscarUsuariosPorRol($search_query, $roleFilter, $current_page, $page_size);
        $cantidadUsuarios = $usuario->contarUsuariosPorRol($search_query, $roleFilter);
    } else {
        $usuarios = $usuario->buscarUsuarios($search_query, $current_page, $page_size);
        $cantidadUsuarios = $usuario->contarUsuarios($search_query);
    }
} else {
    if ($roleFilter) {
        $usuarios = $usuario->listarUsuariosPorRol($roleFilter, $current_page, $page_size);
        $cantidadUsuarios = $usuario->contarUsuariosPorRol($search_query, $roleFilter);
    } else {
        $usuarios = $usuario->listarUsuarios($current_page, $page_size);
        $cantidadUsuarios = $usuario->contarUsuarios();
    }
}


$total_pages = ceil($cantidadUsuarios / $page_size);

if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'eliminado') {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Mostrando SweetAlert');
            Swal.fire({
                icon: 'success',
                title: 'Usuario eliminado',
                text: 'El usuario ha sido eliminado exitosamente.',
                confirmButtonText: 'Aceptar'
            });
        });
    </script>";
}

?>

<header>
    <h1>Gestionar Usuarios</h1>
    <div class="search-container">
        <input type="text" id="buscarUsuario" placeholder="Buscar usuario..." value="<?= htmlspecialchars($search_query) ?>">
        <button id="searchButton">Buscar</button>
    </div>
    <div class="filter-container">
        <select id="roleFilter" name="roleFilter">
            <option value="">-- Selecciona un rol --</option>
            <option value="1" <?= $roleFilter === '1' ? 'selected' : '' ?>>Administrador</option>
            <option value="2" <?= $roleFilter === '2' ? 'selected' : '' ?>>Cliente</option>
            <option value="3" <?= $roleFilter === '3' ? 'selected' : '' ?>>Empleado</option>
            <option value="4" <?= $roleFilter === '3' ? 'selected' : '' ?>>Repartidor</option>
        </select>
        <button id="applyRoleFilterButton">Aplicar Filtro</button>
    </div>

</header>

<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($usuarios as $u) {
            echo "<tr>";
            echo "<td>{$u['idUsuario']}</td>";
            echo "<td>{$u['nickname']}</td>";
            echo "<td>{$u['email']}</td>";
            echo "<td>{$u['nombreRol']}</td>";
            echo "<td>";
            echo "<a class='btn btn-primary btn-sm me-2' href='?page=editarUsuario&idUsuario={$u['idUsuario']}'>Editar</a>";
            echo "<a class='btn-danger' href='#' onclick='eliminarUsuario({$u['idUsuario']})'>Eliminar</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <br><br>
    <a class="boton" href="?page=crearUsuario">Crear Nuevo Usuario</a>
    <br>
    <nav class="pagination">
        <ul>
            <li>
                <a href="?page=gestionUsuarios&current_page=<?= max(1, $current_page - 1) ?>&search_query=<?= urlencode($search_query) ?>&roleFilter=<?= urlencode($roleFilter) ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>Atrás</a>
            </li>
            <p>
                Página <?= $current_page ?> de <?= ceil($cantidadUsuarios / $page_size) ?>
            </p>
            <p>
                Mostrando <?= $page_size * ($current_page - 1) + 1 ?> a <?= min($page_size * $current_page, $cantidadUsuarios) ?> de <?= $cantidadUsuarios ?> usuarios.
            </p>
            <li>
                <a href="?page=gestionUsuarios&current_page=<?= min($current_page + 1, ceil($cantidadUsuarios / $page_size)) ?>&search_query=<?= urlencode($search_query) ?>&roleFilter=<?= urlencode($roleFilter) ?>" <?= $current_page >= ceil($cantidadUsuarios / $page_size) ? 'disabled' : '' ?>>Siguiente</a>
            </li>
        </ul>
    </nav>
</main>

<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('buscarUsuario').value;
        window.location.href = '?page=gestionUsuarios&search_query=' + encodeURIComponent(searchQuery) + '&current_page=1';
    });

    function eliminarUsuario(idUsuario) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡El usuario será eliminado!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'controller/eliminarUsuario.php?accion=eliminar&idUsuario=' + idUsuario;
            }
        });
    }

    document.getElementById('applyRoleFilterButton').addEventListener('click', function() {
        var roleFilter = document.getElementById('roleFilter').value;
        var searchQuery = document.getElementById('buscarUsuario').value;
        window.location.href = '?page=gestionUsuarios&search_query=' + encodeURIComponent(searchQuery) + '&roleFilter=' + encodeURIComponent(roleFilter) + '&current_page=1';
    });
</script>

