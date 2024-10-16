<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/producto.php');

if (isset($_POST['eliminar'])) {
    $idProducto = $_POST['idProducto'];
    
    $producto = new Producto();
    
    if ($producto->eliminarProducto($idProducto)) {
        header('Location: ../?page=gestionProductos&success=Producto eliminado exitosamente');
    } else {
        header('Location: ../?page=gestionProductos&error=Error al eliminar el producto');
    }
    exit;
}

if (isset($_POST['crear'])) {
    $nombreProducto = $_POST['nombreProducto'];
    $codigoBarra = $_POST['codigoBarras'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fechaVencimiento = $_POST['fechaVencimiento'];
    $marca_idMarca = $_POST['marca_idMarca'];
    $categoriaProducto_idCategoriaProducto = $_POST['categoriaProducto_idCategoriaProducto'];

    $producto = new Producto();
    if ($producto->existeNombreProducto($nombreProducto)) {
        header('Location: ../?page=crearProducto&error=El nombre del producto ya existe.');
        exit;
    }
    if ($producto->existeCodigoBarras($codigoBarra)) {
        header('Location: ../?page=crearProducto&error=El código de barras ya existe.');
        exit;
    }

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tienda/assets/';
        $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);
        if (!is_writable($uploadDir)) {
            error_log('El directorio de subida no es escribible.');
            header('Location: ../?page=crearProducto&error=El directorio de subida no es escribible');
            exit;
        }
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
            $imagen = basename($_FILES['imagen']['name']);
        } else {
            error_log('Error al mover el archivo. Código de error: ' . $_FILES['imagen']['error']);
            header('Location: ../?page=crearProducto&error=No se pudo mover el archivo de imagen');
            exit;
        }
    } else {
        $imagen = '';
    }

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

    $producto = new Producto();

    $imagen = $_POST['imagenActual'];
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tienda/assets/';
        $uploadFile = $uploadDir . basename($_FILES['imagen']['name']);
        if (!is_writable($uploadDir)) {
            error_log('El directorio de subida no es escribible.');
            header('Location: ../?page=editarProducto&error=El directorio de subida no es escribible');
            exit;
        }
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadFile)) {
            $imagen = basename($_FILES['imagen']['name']);
        } else {
            error_log('Error al mover el archivo. Código de error: ' . $_FILES['imagen']['error']);
            header('Location: ../?page=editarProducto&error=No se pudo mover el archivo de imagen');
            exit;
        }
    }

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
?>