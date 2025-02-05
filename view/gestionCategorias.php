<?php 
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

require_once('model/categoriaProducto.php');
$categoria = new CategoriaProducto();

if ($search_query) {
    $categorias = $categoria->buscarCategorias($search_query, $current_page, $page_size);
    $cantidadCategorias = $categoria->contarCategorias($search_query);
} else {
    $categorias = $categoria->listarCategoriasPaginadas($current_page, $page_size);
    $cantidadCategorias = $categoria->cantidadCategorias();
}

$total_pages = ceil($cantidadCategorias / $page_size);
?>
<header>
    <h1>Gestión de Categorías</h1>
    <div class="search-container">
        <input type="text" id="buscarCategoria" placeholder="Buscar categoría..." value="<?= htmlspecialchars($search_query) ?>">
        <button id="searchButton">Buscar</button>
    </div>
</header>

<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categorias as $c): ?>
                <tr>
                    <td><?= htmlspecialchars($c['idCategoriaProducto']) ?></td>
                    <td><?= htmlspecialchars($c['nombreCategoria']) ?></td>
                    <td>
                        <a class='btn btn-primary btn-sm me-2' href='?page=editarCategoria&idCategoriaProducto=<?= $c['idCategoriaProducto'] ?>'>Editar</a>
                        <a class='btn-danger' href='controller/categoriaControlador.php?accion=eliminar&idCategoriaProducto=<?= $c['idCategoriaProducto'] ?>' onclick='return confirmarEliminacion(<?= $c['idCategoriaProducto'] ?>)'>Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav class="pagination">
        <ul>
            <li>
                <a href="?page=gestionCategorias&current_page=<?= max(1, $current_page - 1) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>Atrás</a>
            </li>
            <p>Página <?= $current_page ?> de <?= $total_pages ?></p>
            <li>
                <a href="?page=gestionCategorias&current_page=<?= min($current_page + 1, $total_pages) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Siguiente</a>
            </li>
        </ul>
    </nav>
</main>

<script src="assets/javascript/validaciones.js"></script>
<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('buscarCategoria').value;
        window.location.href = '?page=gestionCategorias&search_query=' + encodeURIComponent(searchQuery) + '&current_page=1';
    });

    function confirmarEliminacion(idCategoriaProducto) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡La categoría será eliminada!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'controller/categoriaControlador.php?accion=eliminar&idCategoriaProducto=' + idCategoriaProducto;
            }
        });
    }
</script>
