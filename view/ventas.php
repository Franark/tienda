<header>
    <h1>Catálogo de Productos</h1>
</header>
<main>
    <div class="productos">
        <?php 
        require_once('model/producto.php');
        $producto = new Producto();
        $productos = $producto->listarProductos();
        foreach ($productos as $p): ?>
            <div class="producto">
                <img src="assets/<?php echo $p['imagen']; ?>" alt="<?php echo $p['nombreProducto']; ?>">
                <h2><?php echo $p['nombreProducto']; ?></h2>
                <p>Precio: $<?php echo number_format($p['precio'], 2, ',', '.'); ?></p>
                <a href="?page=verProducto&idProducto=<?php echo $p['idProducto']; ?>">Ver más</a>
            </div>
        <?php endforeach; ?>
    </div>
</main>
