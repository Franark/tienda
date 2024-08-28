<?php
require_once ('conexion.php');

class CategoriaProducto {
    public $idCategoriaProducto;
    public $nombreCategoria;

    public function __construct($idCategoriaProducto = null, $nombreCategoria = null) {
        $this->idCategoriaProducto = $idCategoriaProducto;
        $this->nombreCategoria = $nombreCategoria;
    }

    public function listarCategorias() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM categoriaProducto";
        $result = $conn->query($query);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        $conexion->desconectar();
        return $categorias;
    }

    public function crearCategoria() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO categoriaProducto (nombreCategoria) VALUES ('$this->nombreCategoria')";
        
        $resultado = $conn->query($query);
        if ($resultado) {
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en la creación de categoría: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    }


    public function actualizarCategoria() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE categoriaProducto SET nombreCategoria='$this->nombreCategoria' WHERE idCategoriaProducto='$this->idCategoriaProducto'";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function eliminarCategoria($idCategoriaProducto) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM categoriaProducto WHERE idCategoriaProducto='$idCategoriaProducto'";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function obtenerCategoriaPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM categoriaProducto WHERE idCategoriaProducto = '$id'";
        $result = $conn->query($query);

        $categoria = null;
        if ($result->num_rows > 0) {
            $categoria = $result->fetch_assoc();
        }

        $conexion->desconectar();
        return $categoria;
    }

    /**
     * Get the value of idCategoriaProducto
     */ 
    public function getIdCategoriaProducto()
    {
        return $this->idCategoriaProducto;
    }

    /**
     * Set the value of idCategoriaProducto
     *
     * @return  self
     */ 
    public function setIdCategoriaProducto($idCategoriaProducto)
    {
        $this->idCategoriaProducto = $idCategoriaProducto;

        return $this;
    }

    /**
     * Get the value of nombreCategoria
     */ 
    public function getNombreCategoria()
    {
        return $this->nombreCategoria;
    }

    /**
     * Set the value of nombreCategoria
     *
     * @return  self
     */ 
    public function setNombreCategoria($nombreCategoria)
    {
        $this->nombreCategoria = $nombreCategoria;

        return $this;
    }
}
?>
