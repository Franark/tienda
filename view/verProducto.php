<?php
require_once('model/producto.php');

$id = $_GET['idProducto'];
$producto = new Producto();
$p = $producto->obtenerProductoPorId($id);

if (isset($_POST['add_to_cart'])) {
    $cantidad = $_POST['cantidad'] ?? 1;

    if ($p['stock'] >= $cantidad) {
        $productoCarrito = [
            'idProducto' => $p['idProducto'],
            'nombreProducto' => $p['nombreProducto'],
            'precio' => $p['precio'],
            'cantidad' => $cantidad,
            'imagen' => $p['imagen']
        ];

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
        $encontrado = false;
        foreach ($_SESSION['carrito'] as &$productoEnCarrito) {
            if ($productoEnCarrito['idProducto'] == $p['idProducto']) {
                $productoEnCarrito['cantidad'] += $cantidad; 
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $_SESSION['carrito'][] = $productoCarrito;
        }

        $producto->actualizarStock($p['idProducto'], -$cantidad);

        header("Location: ?page=verProducto&idProducto=" . $p['idProducto']."&success=Producto añadido");
        exit();
    } else {
        echo "No hay suficiente stock disponible.";
    }
}
?>


<header>
    <h1><?php echo $p['nombreProducto']; ?></h1>
</header>
<main>
    <?php if (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                confirmButtonText: 'OK',
            });
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo htmlspecialchars($_GET['success']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    <div class="producto-detalle">
        <img src="assets/<?php echo $p['imagen']; ?>" alt="<?php echo $p['nombreProducto']; ?>">
        <h2><?php echo $p['nombreProducto']; ?></h2>
        <p>Precio: $<?php echo number_format($p['precio'], 2, ',', '.'); ?></p>
        <p>Stock disponible: <?php echo $p['stock']; ?></p>
        <p>Fecha de vencimiento: <?php echo date('d-m-Y', strtotime($p['fechaVencimiento'])); ?></p>
        <form method="post">
            <label for="cantidad">Cantidad:</label>
            <input type="number" name="cantidad" id="cantidad" value="1" min="1" max="<?php echo $p['stock']; ?>">
            <button type="submit" name="add_to_cart">Añadir al carrito</button>
        </form>
    </div>
</main>
<script src="assets/javascript/verProducto.js"></script>