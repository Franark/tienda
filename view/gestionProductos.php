<?php 
$page_size = 10;
$current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'asc';

require_once('model/producto.php');
$producto = new Producto();
$producto->page_size = $page_size;
$producto->current_page = $current_page;

if ($filter === 'sin_stock') {
    $productos = $producto->obtenerProductosSinStock();
    $cantidadProductos = count($productos);
} elseif ($filter === 'por_vencer') {
    $productos = $producto->productosPorVencer();
    $cantidadProductos = count($productos);
} elseif ($filter === 'precio') {
    if ($order === '') {
        $productos = $producto->listarProductos();
    } else {
        $productos = $producto->listarProductosPorPrecio($order);
    }
    $cantidadProductos = count($productos);
} elseif ($filter === 'stock') {
    if ($order === '') {
        $productos = $producto->listarProductos();
    } else {
        $productos = $producto->listarProductosPorStock($order);
    }
    $cantidadProductos = count($productos);
} elseif ($filter === 'fecha_vencimiento') {
    if ($order === '') {
        $productos = $producto->listarProductos();
    } else {
        $productos = $producto->listarProductosPorFechaVencimiento($order);
    }
    $cantidadProductos = count($productos);
} elseif ($filter === 'inactivos') {
    $productos = $producto->listarProductosInactivos();
    $cantidadProductos = count($productos);
} elseif ($search_query) {
    $productos = $producto->buscarProductos($search_query, '', '', '', $current_page, $page_size);
    $cantidadProductos = $producto->contarProductos($search_query, '', '', '');
} else {
    $productos = $producto->listarProductos();
    $cantidadProductos = $producto->cantidadProductos();
}

if ($filter === 'stock') {
    $productos = $producto->listarProductosPorStock($order);
    $cantidadProductos = count($productos);
} elseif ($filter === 'fecha_vencimiento') {
    $productos = $producto->listarProductosPorFechaVencimiento($order);
    $cantidadProductos = count($productos);
}


$total_pages = ceil($cantidadProductos / $page_size);
?>

<header>
    <h1>Gestionar Productos</h1>
    
    <div class="search-container">
        <input type="text" id="buscarProducto" placeholder="Buscar producto..." value="<?= htmlspecialchars($search_query) ?>">
        <button id="searchButton">Buscar</button>
    </div>
    
    <div class="filter-container">
        <select id="filterSelect">
            <option value="">-- Selecciona un filtro --</option>
            <option value="sin_stock" <?= $filter === 'sin_stock' ? 'selected' : '' ?>>Productos sin stock</option>
            <option value="por_vencer" <?= $filter === 'por_vencer' ? 'selected' : '' ?>>Productos por vencer</option>
            <option value="precio" <?= $filter === 'precio' ? 'selected' : '' ?>>Precio</option>
            <option value="stock" <?= $filter === 'stock' ? 'selected' : '' ?>>Stock</option>
            <option value="fecha_vencimiento" <?= $filter === 'fecha_vencimiento' ? 'selected' : '' ?>>Fecha Vencimiento</option>
            <option value="inactivos" <?= $filter === 'inactivos' ? 'selected' : '' ?>>Productos inactivos</option>
        </select>
        <button id="applyFilterButton">Aplicar Filtro</button>
    </div>
</header>

<main>
    <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Código de Barra</th>
            <th>
                <a href="#" onclick="ordenarProductos('precio')">
                    Precio <?= $order === 'asc' ? '↑' : '↓' ?>
                </a>
            </th>
            <th>
                <a href="#" onclick="ordenarProductos('stock')">
                    Stock <?= $order === 'asc' ? '↑' : '↓' ?>
                </a>
            </th>
            <th>
                <a href="#" onclick="ordenarProductos('fechaVencimiento')">
                    Fecha Vencimiento <?= $order === 'asc' ? '↑' : '↓' ?>
                </a>
            </th>
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
            echo "<td>" . number_format($p['precio'], 2, '.', ',') . "</td>";
            echo "<td>{$p['stockActual']}</td>";
            echo "<td>" . date('d-m-Y', strtotime($p['fechaVencimiento'])) . "</td>";
            echo "<td>" . (isset($p['nombreMarca']) ? $p['nombreMarca'] : 'Sin Marca') . "</td>";
            echo "<td>" . (isset($p['nombreCategoria']) ? $p['nombreCategoria'] : 'Sin Categoría') . "</td>";
            echo "<td><img src='assets/{$p['imagenPrincipal']}' alt='Imagen del producto' width='50'></td>";
            echo "<td>";
            echo "<div class='action-buttons'>";
            if ($p['activo'] == 1) {
                echo "<a class='btn btn-primary btn-sm' href='?page=editarProducto&idProducto={$p['idProducto']}'>Editar</a>";
                echo "<a class='btn btn-danger btn-sm' href='#' onclick='confirmarInactivacion({$p['idProducto']})'>Inactivo</a>";
            } else {
                echo "<a class='btn btn-success btn-sm' href='#' onclick='activarProducto({$p['idProducto']})'>Activar</a>";
            }
            echo "</div>";
            echo "</td>";

            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <br><br>
    <a class="boton" href="?page=crearProducto">Crear Nuevo Producto</a>
    <a class="boton" href="?page=actualizarPrecios">Actualizar los precios de los Productos</a>
    <a class="boton" href="controller/exportarProductos.php?filter=<?= urlencode($filter) ?>&search_query=<?= urlencode($search_query) ?>">Descargar Listado (Excel)</a>
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
    function confirmarInactivacion(idProducto) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡El producto será marcado como inactivo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, marcar como inactivo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'controller/productoControlador.php?accion=eliminar&idProducto=' + idProducto;
            }
        });
    }

    function activarProducto(idProducto) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡El producto será activado nuevamente!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, activarlo!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'controller/productoControlador.php?accion=activar&idProducto=' + idProducto;
            }
        });
    }


    document.getElementById('searchButton').addEventListener('click', function() {
        var searchQuery = document.getElementById('buscarProducto').value;
        window.location.href = '?page=gestionProductos&search_query=' + encodeURIComponent(searchQuery) + '&current_page=1';
    });

    document.getElementById('applyFilterButton').addEventListener('click', function() {
        var filterValue = document.getElementById('filterSelect').value;
        window.location.href = '?page=gestionProductos&filter=' + filterValue + '&current_page=1';
    });
    function ordenarProductos(criterio) {
        const currentOrder = new URLSearchParams(window.location.search).get('order');
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
        window.location.href = `?page=gestionProductos&filter=${criterio}&order=${newOrder}`;
    }

    function actualizarTabla(productos) {
        const tabla = document.querySelector('table tbody');
        let html = '';

        productos.forEach(producto => {
            html += `
                <tr>
                    <td>${producto.idProducto}</td>
                    <td>${producto.nombreProducto}</td>
                    <td>${producto.codigoBarras}</td>
                    <td>${producto.precio}</td>
                    <td>${producto.stockActual}</td>
                    <td>${producto.fechaVencimiento}</td>
                    <td>${producto.nombreMarca}</td>
                    <td>${producto.nombreCategoria}</td>
                    <td><img src="assets/${producto.imagen}" alt="${producto.nombreProducto}" width="50"></td>
                    <td>
                        ${producto.activo == 1 ? `<a href='#' onclick='confirmarInactivacion(${producto.idProducto})'>Marcar como Inactivo</a>` : `<a href='#' onclick='activarProducto(${producto.idProducto})'>Activar</a>`}
                    </td>
                </tr>
            `;
        });

        tabla.innerHTML = html;
    }


</script>