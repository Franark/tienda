<?php
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
    exit();
}

$carrito = $_SESSION['carrito'];
$total = 0;
?>

<h1>Carrito de Compras</h1>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Imagen</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($carrito as $index => $producto) : ?>
            <tr>
                <td><?php echo $producto['nombreProducto']; ?></td>
                <td><img src="assets/<?php echo $producto['imagen']; ?>" alt="Imagen del producto" width="50"></td>
                <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                <td>
                    <form method="post" action="controller/carritoControlador.php">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="number" name="cantidad" value="<?php echo $producto['cantidad']; ?>" >
                        <button type="submit" name="accion" value="actualizar">Actualizar</button>
                    </form>
                </td>
                <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.'); ?></td>
                <td>
                    <form method="post" action="controller/carritoControlador.php">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <button type="submit" name="accion" value="eliminar">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php $total += $producto['precio'] * $producto['cantidad']; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<p>Total: $<?php echo number_format($total, 2, ',', '.'); ?></p>

<a href="?page=procesoPago" class="btn">Proceder al pago</a>
