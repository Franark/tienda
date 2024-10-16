<?php
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
    exit();
}

$carrito = $_SESSION['carrito'];
$total = 0;
?>

<h1>Proceso de Pago</h1>

<table>
    <thead>
        <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($carrito as $producto) : ?>
            <tr>
                <td><?php echo $producto['nombreProducto']; ?></td>
                <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                <td><?php echo $producto['cantidad']; ?></td>
                <td>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2, ',', '.'); ?></td>
            </tr>
            <?php $total += $producto['precio'] * $producto['cantidad']; ?>
        <?php endforeach; ?>
    </tbody>
</table>

<p>Total a pagar: $<?php echo number_format($total, 2, ',', '.'); ?></p>

<form method="post" action="controller/procesoPagoControlador.php">
    <input type="hidden" name="total" value="<?php echo $total; ?>">
    <button class="confirmar-compra" onclick='return confirm("¿Está seguro de que desea hacer la compra?")' type="submit" name="confirmarPago">Confirmar Compra</button>
</form>

<a href="?page=carrito">Volver al carrito</a>