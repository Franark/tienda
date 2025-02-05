<form action="controller/productoControlador.php" method="post">
    <label for="tipo">Actualizar precios por:</label>
    <select name="tipo" id="tipo" onchange="mostrarOpciones()">
        <option value="todos">Todos los productos</option>
        <option value="marca">Por Marca</option>
        <option value="categoria">Por Categoría</option>
    </select>

    <div id="opcionesMarca" style="display:none;">
        <label for="marca">Marca:</label>
        <select name="marca" id="marca">
            <?php
            require_once('model/marca.php');
            $marca = new Marca();
            $marcas = $marca->listarMarcas();
            
            foreach ($marcas as $m) {
                echo "<option value='{$m['idMarca']}'>{$m['nombreMarca']}</option>";
            }
            ?>
        </select>
    </div>

    <div id="opcionesCategoria" style="display:none;">
        <label for="categoria">Categoría:</label>
        <select name="categoria" id="categoria">
            <?php
            require_once('model/categoriaProducto.php');
            $categoria = new CategoriaProducto();
            $categorias = $categoria->listarCategorias();
            
            foreach ($categorias as $c) {
                echo "<option value='{$c['idCategoriaProducto']}'>{$c['nombreCategoria']}</option>";
            }
            ?>
        </select>
    </div>

    <label for="porcentaje">Porcentaje de ajuste:</label>
    <input type="number" name="porcentaje" step="0.01" min="0.01" required>

    <button type="submit">Actualizar Precios</button>
</form>
<?php
if (isset($_GET['success'])) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{$_GET['success']}',
            confirmButtonText: 'Aceptar'
        });
    </script>";
}

if (isset($_GET['error'])) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{$_GET['error']}',
            confirmButtonText: 'Aceptar'
        });
    </script>";
}
?>

<script>
function mostrarOpciones() {
    var tipo = document.getElementById('tipo').value;
    document.getElementById('opcionesMarca').style.display = (tipo === 'marca') ? 'block' : 'none';
    document.getElementById('opcionesCategoria').style.display = (tipo === 'categoria') ? 'block' : 'none';
}
if (window.location.search.includes('success') || window.location.search.includes('error')) {
    history.replaceState({}, document.title, window.location.pathname);
}
</script>
