<?php
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(0, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

require_once('model/producto.php');
$producto = new Producto();
$producto->page_size = $page_size;
$producto->current_page = $current_page;

if ($search_query) {
    $productos = $producto->buscarProductos($search_query, '', '', '', $current_page, $page_size);
    $cantidadProductos = $producto->contarProductos($search_query, '', '', '');
} else {
    $productos = $producto->listarProductos();
    $cantidadProductos = $producto->cantidadProductos();
}

$total_pages = ceil($cantidadProductos / $page_size);
?>
<header>
    <h1>Gestionar Productos</h1>
    <input type="text" id="buscarProducto" placeholder="Buscar producto..." value="<?= htmlspecialchars($search_query) ?>">
    <button id="searchButton">Buscar</button>
</header>

<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código de Barra</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Fecha Vencimiento</th>
                <th>Marca</th>
                <th>Categoría</th>
                <th>Imagen</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($productos as $p) {
                echo "<tr>";
                echo "<td>{$p['idProducto']}</td>";
                echo "<td>{$p['nombreProducto']}</td>";
                echo "<td>{$p['codigoBarras']}</td>";
                echo "<td>{$p['precio']}</td>";
                echo "<td>{$p['stock']}</td>";
                echo "<td>{$p['fechaVencimiento']}</td>";
                echo "<td>{$p['nombreMarca']}</td>";
                echo "<td>{$p['nombreCategoria']}</td>";
                echo "<td><img src='../{$p['imagen']}' alt='Imagen del producto' width='50'></td>";
                echo "<td><a href='?page=editarProducto&idProducto={$p['idProducto']}'>Editar</a> | <a href='controller/productoControlador.php?accion=eliminar&idProducto={$p['idProducto']}' onclick='return confirm(\"¿Está seguro de eliminar este producto?\")'>Eliminar</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br><br>
    <a class="boton" href="?page=crearProducto">Crear Nuevo Producto</a>
    <br>
    <nav class="pagination">
        <ul>
            <li>
                <a href="?page=gestionProductos&current_page=<?= max(1, $current_page - 1) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page <= 1 ? 'disabled' : '' ?>>Atrás</a>
            </li>
            <p>
                Página <?= $current_page ?> de <?= $total_pages ?>
            </p>
            <p>
                Mostrando <?= $page_size * ($current_page - 1) + 1 ?> a <?= min($page_size * $current_page, $cantidadProductos) ?> de <?= $cantidadProductos ?> productos.
            </p>
            <li>
                <a href="?page=gestionProductos&current_page=<?= min($current_page + 1, $total_pages) ?>&search_query=<?= urlencode($search_query) ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Siguiente</a>
            </li>
        </ul>
    </nav>
</main>

<script>
document.getElementById('searchButton').addEventListener('click', function() {
    var searchQuery = document.getElementById('buscarProducto').value;
    window.location.href = '?page=gestionProductos&search_query=' + encodeURIComponent(searchQuery) + '&current_page=1';
});
</script>