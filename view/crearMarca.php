<header>
    <h1>Crear Marca</h1>
</header>
<main>
    <?php if (isset($_GET['error'])): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo htmlspecialchars($_GET['error']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php elseif (isset($_GET['success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo htmlspecialchars($_GET['success']); ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>
    <form id="crearMarcaForm" action="controller/marcaControlador.php" method="post">
        <label for="nombreMarca">Nombre de la Marca:</label>
        <input type="text" id="nombreMarca" name="nombreMarca">
        <br>
        <input type="submit" name="crear" class="boton" value="Crear">
    </form>
</main>
<script src="assets/javascript/validaciones.js"></script>