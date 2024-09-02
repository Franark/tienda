<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('../model/producto.php');

if (isset($_POST['crear'])) {
    $nombreProducto = $_POST['nombreProducto'];
    $codigoBarra = $_POST['codigoBarras'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $marca_idMarca = $_POST['marca_idMarca'];
    $categoriaProducto_idCategoriaProducto = $_POST['categoriaProducto_idCategoriaProducto'];

    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $producto = new Producto();
    $producto->setNombreProducto($nombreProducto);
    $producto->setCodigoBarra($codigoBarra);
    $producto->setPrecio($precio);
    $producto->setStock($stock);
    $producto->setFechaVencimiento($fechaVencimiento);
    $producto->setImagen($imagen);
    $producto->setMarca_idMarca($marca_idMarca);
    $producto->setCategoriaProducto_idCategoriaProducto($categoriaProducto_idCategoriaProducto);

    if ($producto->crearProducto()) {
        header('Location: ../?page=gestionProductos');
    } else {
        header('Location: ../?page=crearProducto&error=Error al crear el producto');
    }
    exit;
}

if (isset($_POST['editar'])) {
    $idProducto = $_POST['idProducto'];
    $nombreProducto = $_POST['nombreProducto'];
    $codigoBarra = $_POST['codigoBarras'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $marca_idMarca = $_POST['marca_idMarca'];
    $categoriaProducto_idCategoriaProducto = $_POST['categoriaProducto_idCategoriaProducto'];

    $imagen = $_POST['imagenActual'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $imagen = 'uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    $producto = new Producto();
    $producto->setIdProducto($idProducto);
    $producto->setNombreProducto($nombreProducto);
    $producto->setCodigoBarra($codigoBarra);
    $producto->setPrecio($precio);
    $producto->setStock($stock);
    $producto->setFechaVencimiento($fechaVencimiento);
    $producto->setImagen($imagen);
    $producto->setMarca_idMarca($marca_idMarca);
    $producto->setCategoriaProducto_idCategoriaProducto($categoriaProducto_idCategoriaProducto);

    $producto->actualizarProducto();
    header('Location: ../?page=gestionProductos');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idProducto = $_GET['idProducto'];

    $producto = new Producto();
    if ($producto->eliminarProducto($idProducto)) {
        header('Location: ../?page=gestionProductos');
    } else {
        header('Location: ../?page=gestionProductos?error=Error al eliminar el producto');
    }
    exit;
}
?>
