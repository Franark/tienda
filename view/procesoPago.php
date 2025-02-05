<?php
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<p>Tu carrito está vacío.</p>";
    exit();
}

$carrito = $_SESSION['carrito'];
$total = 0;

$idUsuario = $_SESSION['idUsuario'];
require_once 'model/direccion.php';
$direccionModel = new Direccion();
$direcciones = $direccionModel->obtenerDireccionesPorUsuario($idUsuario);
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

<?php if (!empty($direcciones)) : ?>
    <form method="post" action="controller/procesoPagoControlador.php">
        <input type="hidden" name="total" value="<?php echo $total; ?>">
        <h3>Selecciona una dirección de envío:</h3>
        <select name="direccion" required>
            <option value="" disabled selected>Selecciona una dirección</option>
            <?php foreach ($direcciones as $direccion): ?>
                <option value="<?php echo $direccion->idDomicilio; ?>">
                    <?php echo "Barrio: " . $direccion->barrio . ', Casa: ' . $direccion->numeroCasa; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <h3>Selecciona un método de pago:</h3>
        <select name="metodo_pago" required>
            <option value="" disabled selected>Selecciona un método de pago</option>
            <option value="Mercado Pago">Mercado Pago</option>
            <option value="Efectivo">Efectivo</option>
        </select>

        <button class="confirmar-compra" id="confirmarCompraBtn" type="button">Confirmar Compra</button>
    </form>
<?php else : ?>
    <p>No tienes direcciones registradas. Por favor, agrega una nueva dirección en tu perfil.</p>
    <a href="?page=perfil" class="btn">Ir a mi perfil</a>
<?php endif; ?>

<a href="?page=carrito" class="btn">Volver al carrito</a>

<script>
    document.getElementById('confirmarCompraBtn').addEventListener('click', function () {
        fetch('controller/perfilControlador.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ accion: 'validarDatosPerfil' })
        })
        .then(response => response.json())
        .then(data => {
            if (!data.completo) {
                Swal.fire({
                    icon: 'error',
                    title: 'Datos incompletos',
                    text: `Por favor completa los siguientes campos: ${data.faltantes.join(', ')}.`,
                });
            } else {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡Estás a punto de confirmar la compra!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, confirmar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector('form').submit();
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error al validar los datos del perfil:', error);
        });
    });
</script>
