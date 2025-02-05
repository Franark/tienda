<?php
$page_size = 4;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$categoriaProducto_idCategoriaProducto = isset($_GET['categoriaProducto_idCategoriaProducto']) ? $_GET['categoriaProducto_idCategoriaProducto'] : '';
$marca_idMarca = isset($_GET['marca_idMarca']) ? $_GET['marca_idMarca'] : '';

require_once('model/producto.php');
$producto = new Producto();
$producto->page_size = $page_size;
$producto->current_page = $current_page;

if ($search_query || $categoriaProducto_idCategoriaProducto || $marca_idMarca) {
    $productos = $producto->buscarProductos($search_query, '', $marca_idMarca, $categoriaProducto_idCategoriaProducto, $current_page, $page_size);
    $cantidadProductos = $producto->contarProductos($search_query, '', $marca_idMarca, $categoriaProducto_idCategoriaProducto);
} else {
    $productos = $producto->listarProductosClientes();
    $cantidadProductos = $producto->cantidadProductos();
}

$total_pages = ceil($cantidadProductos / $page_size);
?>
<header>
    <h1>Catálogo de Productos</h1>
    <input type="text" id="buscarProducto" placeholder="Buscar producto..." value="<?= htmlspecialchars($search_query) ?>">
    <div class="dropdown">
        <button class="dropdown-button">Categoría</button>
        <div class="dropdown-content" id="categoriaDropdown">
            <?php
            require_once('model/categoriaProducto.php');
            $categoria = new CategoriaProducto();
            $categorias = $categoria->listarCategorias();
            foreach ($categorias as $c): ?>
                <a href="#" data-categoria-id="<?php echo $c['idCategoriaProducto'] ?>"><?php echo $c['nombreCategoria'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="dropdown">
        <button class="dropdown-button">Marca</button>
        <div class="dropdown-content" id="marcaDropdown">
            <?php
            require_once('model/marca.php');
            $marca = new Marca();
            $marcas = $marca->listarMarcas();
            foreach ($marcas as $m): ?>
                <a href="#" data-marca-id="<?php echo $m['idMarca'] ?>"><?php echo $m['nombreMarca'] ?></a>
            <?php endforeach; ?>
        </div>
    </div>
    <button id="searchButton">Buscar</button>
</header>
<main>
    <div class="productos">
        <?php foreach ($productos as $p): ?>
            <div class="producto">
                <?php
                $imagenPrincipal = !empty($p['imagenes']) ? $p['imagenes'][0] : 'default-image.jpg';
                ?>
                <img src="assets/<?php echo htmlspecialchars($imagenPrincipal); ?>" alt="<?php echo htmlspecialchars($p['nombreProducto']); ?>">
                <h2><?php echo htmlspecialchars($p['nombreProducto']); ?></h2>
                <p>Precio: $<?php echo number_format($p['precio'], 2, ',', '.'); ?></p>
                <p><?php echo htmlspecialchars($p['nombreMarca']); ?></p>
                <a href="?page=verProducto&idProducto=<?php echo $p['idProducto']; ?>">Ver más</a>
            </div>
        <?php endforeach; ?>
    </div>



    <nav class="pagination">
        <ul>
            <li><a href="?page=catalogoProductos&current_page=<?= max(1, $current_page - 1) ?>&search_query=<?= urlencode($search_query) ?>&categoriaProducto_idCategoriaProducto=<?= urlencode($categoriaProducto_idCategoriaProducto) ?>&marca_idMarca=<?= urlencode($marca_idMarca) ?>">Atrás</a></li>
            <p>Página <?= $current_page ?> de <?= $total_pages ?></p>
            <li><a href="?page=catalogoProductos&current_page=<?= min($current_page + 1, $total_pages) ?>&search_query=<?= urlencode($search_query) ?>&categoriaProducto_idCategoriaProducto=<?= urlencode($categoriaProducto_idCategoriaProducto) ?>&marca_idMarca=<?= urlencode($marca_idMarca) ?>" <?= $current_page >= $total_pages ? 'disabled' : '' ?>>Siguiente</a></li>
        </ul>
    </nav>
</main>

<script>
document.getElementById('searchButton').addEventListener('click', function() {
    var searchQuery = document.getElementById('buscarProducto').value;
    var categoriaId = document.querySelector('#categoriaDropdown a.selected') ? document.querySelector('#categoriaDropdown a.selected').dataset.categoriaId : '';
    var marcaId = document.querySelector('#marcaDropdown a.selected') ? document.querySelector('#marcaDropdown a.selected').dataset.marcaId : '';
    window.location.href = '?page=catalogoProductos&search_query=' + encodeURIComponent(searchQuery) + '&categoriaProducto_idCategoriaProducto=' + categoriaId + '&marca_idMarca=' + marcaId + '&current_page=1';
});

document.querySelectorAll('#categoriaDropdown a').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('#categoriaDropdown a').forEach(a => a.classList.remove('selected'));
        this.classList.add('selected');
        var searchQuery = document.getElementById('buscarProducto').value;
        var categoriaId = this.dataset.categoriaId;
        var marcaId = document.querySelector('#marcaDropdown a.selected') ? document.querySelector('#marcaDropdown a.selected').dataset.marcaId : '';
        window.location.href = '?page=catalogoProductos&search_query=' + encodeURIComponent(searchQuery) + '&categoriaProducto_idCategoriaProducto=' + categoriaId + '&marca_idMarca=' + marcaId + '&current_page=1';
    });
});

document.querySelectorAll('#marcaDropdown a').forEach(function(element) {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('#marcaDropdown a').forEach(a => a.classList.remove('selected'));
        this.classList.add('selected');
        var searchQuery = document.getElementById('buscarProducto').value;
        var marcaId = this.dataset.marcaId;
        var categoriaId = document.querySelector('#categoriaDropdown a.selected') ? document.querySelector('#categoriaDropdown a.selected').dataset.categoriaId : '';
        window.location.href = '?page=catalogoProductos&search_query=' + encodeURIComponent(searchQuery) + '&categoriaProducto_idCategoriaProducto=' + categoriaId + '&marca_idMarca=' + marcaId + '&current_page=1';
    });
});

</script>
