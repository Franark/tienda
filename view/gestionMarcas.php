<?php 
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

require_once('model/marca.php');
$marca = new Marca();
$marca->page_size = $page_size;
$marca->current_page = $current_page;

if ($search_query) {
    $marcas = $marca->buscarMarcas($search_query, $current_page, $page_size);
    $cantidadMarcas = $marca->contarMarcas($search_query);

} else {
    $marcas = $marca->listarMarca($current_page, $page_size);
    $cantidadMarcas = $marca->cantidadMarcas();
}

$total_pages = ceil($cantidadMarcas / $page_size);
?>
<header>
    <h1>Gestionar Marcas</h1>
    <div class="search-container">
        <input type="text" id="buscarMarca" placeholder="Buscar marca..." value="<?= htmlspecialchars($search_query) ?>">
        <button id="searchButton">Buscar</button>
    </div>
</header>

<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($marcas as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m['idMarca']) ?></td>
                    <td><?= htmlspecialchars($m['nombreMarca']) ?></td>
                    <td>
                        <a class='btn btn-primary btn-sm me-2' href="?page=editarMarca&idMarca=<?= $m['idMarca'] ?>">Editar</a>
                        <a class='btn-danger' href="#" onclick="confirmarEliminacion(<?= $m['idMarca'] ?>)">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <nav class="pagination">
        <ul>
            <li>
                <a href="?page=gestionMarcas&current_page=<?= max(1, $current_page - 1) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>Atrás</a>
            </li>
            <p>Página <?= $current_page ?> de <?= $total_pages ?></p>
            <li>
                <a href="?page=gestionMarcas&current_page=<?= min($current_page + 1, $total_pages) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Siguiente</a>
            </li>
        </ul>
    </nav>
</main>

<script src="assets/javascript/validaciones.js"></script>
<script>
    document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('buscarMarca').value;
        window.location.href = '?page=gestionMarcas&search_query=' + encodeURIComponent(searchQuery) + '&current_page=1';
    });

    function confirmarEliminacion(idMarca) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡La marca será eliminada!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'controller/marcaControlador.php?accion=eliminar&idMarca=' + idMarca;
            }
        });
    }

</script>