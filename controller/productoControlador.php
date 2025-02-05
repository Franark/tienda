<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../model/producto.php');
require_once('../model/categoriaProducto.php');

if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    
    $producto = new Producto();
    
    if ($producto->eliminarProducto($idProducto)) {
        header('Location: ../?page=gestionProductos&success=Producto marcado como inactivo exitosamente');
    } else {
        header('Location: ../?page=editarProducto&idProducto=' . $idProducto . '&error=Mensaje de error');
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

    $imagenes = [];
    if (isset($_FILES['imagenes'])) {
        foreach ($_FILES['imagenes']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['imagenes']['error'][$key] == 0) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tienda/assets/';
                $uploadFile = $uploadDir . basename($_FILES['imagenes']['name'][$key]);
                
                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $imagenes[] = basename($_FILES['imagenes']['name'][$key]);
                } else {
                    header('Location: ../?page=crearProducto&error=Error al subir una de las imágenes');
                    exit;
                }
            }
        }
    }

    $producto->setNombreProducto($nombreProducto);
    $producto->setCodigoBarra($codigoBarra);
    $producto->setPrecio($precio);
    $producto->setStock($stock);
    $producto->setFechaVencimiento($fechaVencimiento);
    $producto->setMarca_idMarca($marca_idMarca);
    $producto->setCategoriaProducto_idCategoriaProducto($categoriaProducto_idCategoriaProducto);

    if ($producto->crearProducto()) {
        foreach ($imagenes as $imagen) {
            $producto->guardarImagenProducto($producto->getIdProducto(), $imagen);
        }
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

    if (isset($_POST['eliminarImagenes'])) {
        foreach ($_POST['eliminarImagenes'] as $rutaImagen) {
            $imagenCompleta = $_SERVER['DOCUMENT_ROOT'] . '/tienda/assets/' . $rutaImagen;
            if (file_exists($imagenCompleta)) {
                unlink($imagenCompleta);
            }
            if (!$producto->eliminarImagenDeBaseDatos($rutaImagen)) {
                header('Location: ../?page=editarProducto&idProducto=' . $idProducto . '&error=No se pudo eliminar la imagen ' . htmlspecialchars($rutaImagen));
                exit;
            }
        }
    }    

    if (isset($_FILES['nuevasImagenes'])) {
        foreach ($_FILES['nuevasImagenes']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['nuevasImagenes']['error'][$key] == 0) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/tienda/assets/';
                $uploadFile = $uploadDir . basename($_FILES['nuevasImagenes']['name'][$key]);

                if (move_uploaded_file($tmpName, $uploadFile)) {
                    $producto->guardarImagenProducto($idProducto, basename($_FILES['nuevasImagenes']['name'][$key]));
                }
            }
        }
    }

    $producto->setIdProducto($idProducto);
    $producto->setNombreProducto($nombreProducto);
    $producto->setCodigoBarra($codigoBarra);
    $producto->setPrecio($precio);
    $producto->setStock($stock);
    $producto->setFechaVencimiento($fechaVencimiento);
    $producto->setMarca_idMarca($marca_idMarca);
    $producto->setCategoriaProducto_idCategoriaProducto($categoriaProducto_idCategoriaProducto);

    $producto->actualizarProducto();

    header('Location: ../?page=verProducto&idProducto=' . $idProducto );
    exit;
}

if (isset($_GET['ordenar_por']) && isset($_GET['order'])) {
    $criterio = $_GET['ordenar_por'];
    $order = $_GET['order'];

    $producto = new Producto();
    switch ($criterio) {
        case 'precio':
            $productos = $producto->listarProductosPorPrecio($order);
            break;
        case 'stock':
            $productos = $producto->listarProductosPorStock($order);
            break;
        case 'fechaVencimiento':
            $productos = $producto->listarProductosPorFechaVencimiento($order);
            break;
        default:
            $productos = $producto->listarProductos();
    }

    echo json_encode($productos);
    exit;
}


if (isset($_POST['accion']) && isset($_POST['idProducto']) && isset($_POST['cantidad'])) {
    $idProducto = $_POST['idProducto'];
    $cantidad = $_POST['cantidad'];
    $accion = $_POST['accion'];

    $producto = new Producto();

    if ($accion === 'aceptado') {
        $producto->actualizarStock($idProducto, -$cantidad);
    } else if ($accion === 'cancelado') {
        $producto->actualizarStock($idProducto, $cantidad);
    }

    echo json_encode(["success" => true]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
    require_once('../model/producto.php');
    $producto = new Producto();
    $tipo = $_POST['tipo'];
    $porcentaje = floatval($_POST['porcentaje']);

    switch ($tipo) {
        case 'todos':
            $resultado = $producto->actualizarPreciosParaTodos($porcentaje / 100);
            break;
        case 'marca':
            if (isset($_POST['marca'])) {
                $marca_id = intval($_POST['marca']);
                $resultado = $producto->actualizarPreciosPorMarca($marca_id, $porcentaje / 100);
            } else {
                header('Location: ../?page=actualizarPrecios&error=Debe seleccionar una marca');
                exit;
            }
            break;
        case 'categoria':
            if (isset($_POST['categoria'])) {
                $categoria_id = intval($_POST['categoria']);
                $resultado = $producto->actualizarPreciosPorCategoria($categoria_id, $porcentaje / 100);
            } else {
                header('Location: ../?page=actualizarPrecios&error=Debe seleccionar una categoría');
                exit;
            }
            break;
        default:
            header('Location: ../?page=actualizarPrecios&error=Tipo de actualización no válido');
            exit;
    }
    
    if ($resultado) {
        header('Location: ../?page=actualizarPrecios&success=Precios actualizados correctamente');
    } else {
        error_log("Error al actualizar precios: Tipo={$tipo}, Resultado={$resultado}");
        header('Location: ../?page=actualizarPrecios&error=Error al actualizar los precios');
    }
    exit;
    
}

if (isset($_GET['accion']) && $_GET['accion'] === 'activar' && isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];
    
    $producto = new Producto();
    
    if ($producto->activarProducto($idProducto)) {
        header('Location: ../?page=gestionProductos&success=Producto activado exitosamente');
    } else {
        header('Location: ../?page=gestionProductos&error=Error al activar el producto');
    }
    exit;
}
?>