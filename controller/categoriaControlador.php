<?php
require_once('../model/categoriaProducto.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear'])) {
    $nombreCategoria = $_POST['nombreCategoria'];

    $categoria = new CategoriaProducto();
    $categoria->setNombreCategoria($nombreCategoria);

    if ($categoria->crearCategoria()) {
        header('Location: ../?page=gestionCategorias');
    } else {
        header('Location: ../?page=crearCategoria&error=Error al crear la categoría');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $idCategoriaProducto = $_POST['idCategoriaProducto'];
    $nombreCategoria = $_POST['nombreCategoria'];

    $categoria = new CategoriaProducto();
    $categoria->setIdCategoriaProducto($idCategoriaProducto);
    $categoria->setNombreCategoria($nombreCategoria);

    $categoria->actualizarCategoria($idCategoriaProducto, $nombreCategoria);
    header('Location: ../?page=gestionCategorias');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar') {
    $idCategoriaProducto = $_GET['idCategoriaProducto'];

    $categoria = new CategoriaProducto();
    if ($categoria->eliminarCategoria($idCategoriaProducto)) {
        header('Location: ../?page=gestionCategorias');
    } else {
        header('Location: ../?page=gestionCategorias&error=Error al eliminar la categoría');
    }
}
?>
