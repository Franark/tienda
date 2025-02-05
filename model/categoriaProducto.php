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
    
        $query = "DELETE FROM categoriaProducto WHERE idCategoriaProducto = ?";
        $insertar = $conn->prepare($query);
    
        $resultado = $insertar->execute([$idCategoriaProducto]);
        
        $conexion->desconectar();
        return $resultado;
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

    public function listarCategoriasPaginadas($current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $offset = ($current_page - 1) * $page_size;
    
        $query = "SELECT * FROM categoriaProducto LIMIT $offset, $page_size";
        $result = $conn->query($query);
    
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    
        $conexion->desconectar();
        return $categorias;
    }
    
    public function buscarCategorias($search_query, $current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $offset = ($current_page - 1) * $page_size;
    
        $query = "SELECT * FROM categoriaProducto WHERE nombreCategoria LIKE ? LIMIT $offset, $page_size";
        $stmt = $conn->prepare($query);
        $search_param = '%' . $search_query . '%';
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
    
        $conexion->desconectar();
        return $categorias;
    }
    
    public function cantidadCategorias() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) as total FROM categoriaProducto";
        $result = $conn->query($query);
    
        $row = $result->fetch_assoc();
        $conexion->desconectar();
    
        return $row['total'];
    }
    
    public function contarCategorias($search_query) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT COUNT(*) as total FROM categoriaProducto WHERE nombreCategoria LIKE ?";
        $stmt = $conn->prepare($query);
        $search_param = '%' . $search_query . '%';
        $stmt->bind_param('s', $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $row = $result->fetch_assoc();
        $conexion->desconectar();
    
        return $row['total'];
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
