<?php
require_once('../model/categoriaProducto.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreCategoria = trim($_POST['nombreCategoria']);

    if (empty($nombreCategoria)) {
        header('Location: ../?page=crearCategoria&error=El nombre de la categoría no puede estar vacío.');
        exit();
    }
    
    if (strlen($nombreCategoria) > 50) {
        header('Location: ../?page=crearCategoria&error=El nombre de la categoría no puede exceder los 50 caracteres.');
        exit();
    }

    $categoria = new CategoriaProducto();
    $categoria->setNombreCategoria($nombreCategoria);

    if ($categoria->crearCategoria()) {
        header('Location: ../?page=gestionCategorias&success=Categoría creada exitosamente.');
    } else {
        header('Location: ../?page=crearCategoria&error=Error al crear la categoría');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $idCategoriaProducto = $_POST['idCategoriaProducto'];
    $nombreCategoria = trim($_POST['nombreCategoria']);

    if (empty($nombreCategoria)) {
        header('Location: ../?page=crearCategoria&error=El nombre de la categoría no puede estar vacío.');
        exit();
    }

    if (strlen($nombreCategoria) > 50) {
        header('Location: ../?page=crearCategoria&error=El nombre de la categoría no puede exceder los 50 caracteres.');
        exit();
    }

    $categoria = new CategoriaProducto();
    $categoria->setIdCategoriaProducto($idCategoriaProducto);
    $categoria->setNombreCategoria($nombreCategoria);

    $categoria->actualizarCategoria($idCategoriaProducto, $nombreCategoria);
    header('Location: ../?page=gestionCategorias&success=Categoría actualizada exitosamente.');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idCategoriaProducto = $_GET['idCategoriaProducto'];

    $categoria = new CategoriaProducto();
    if ($categoria->eliminarCategoria($idCategoriaProducto)) {
        header('Location: ../?page=gestionCategorias&success=Categoría eliminada con éxito');
    } else {
        header('Location: ../?page=gestionCategorias&error=Error al eliminar la categoría');
        exit();
    }
}


?>
