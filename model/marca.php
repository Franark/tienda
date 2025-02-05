<?php
require_once('conexion.php');
require_once('paginacion.php');

class Marca extends Paginacion {
    public $idMarca;
    public $nombreMarca;

    public function __construct($idMarca = null, $nombreMarca = null) {
        $this->idMarca = $idMarca;
        $this->nombreMarca = $nombreMarca;
    }

    public function listarMarca($current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $offset = ($current_page - 1) * $page_size;
        $query = "SELECT * FROM marca LIMIT $offset, $page_size";
        $result = $conn->query($query);
    
        $marcas = [];
        while ($row = $result->fetch_assoc()) {
            $marcas[] = $row;
        }
    
        $conexion->desconectar();
        return $marcas;
    }    

    public function listarMarcas() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM marca";
        $result = $conn->query($query);
        
        $marcas = [];
        while ($row = $result->fetch_assoc()) {
            $marcas[] = $row;
        }
        
        $conexion->desconectar();
        return $marcas;
    }

    public function crearMarca() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO marca (nombreMarca) VALUES ('$this->nombreMarca')";
        
        $resultado = $conn->query($query);
        if ($resultado) {
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en la creación de marca: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    }

    public function actualizarMarca() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE marca SET nombreMarca='$this->nombreMarca' WHERE idMarca='$this->idMarca'";
        $resultado = $conn->query($query);
        if ($resultado) {
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en la creación de marca: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    }

    public function eliminarMarca($idMarca) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM marca WHERE idMarca='$idMarca'";
        $resultado = $conn->query($query);
        if ($resultado) {
            $conexion->desconectar();
            return true;
        } else {
            error_log("Error en la creación de marca: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    }

    public function obtenerMarcaPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM marca WHERE idMarca = '$id'";
        $result = $conn->query($query);

        $marca = null;
        if ($result->num_rows > 0) {
            $marca = $result->fetch_assoc();
        }

        $conexion->desconectar();
        return $marca;
    }

    public function buscarMarcas($search_query, $current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $this->current_page = max(1, $this->current_page);
        $offset = ($this->current_page - 1) * $this->page_size;
        $query = "SELECT * FROM marca WHERE nombreMarca LIKE '%$search_query%' LIMIT $offset, $this->page_size";

        $result = $conn->query($query);

        $marcas = [];
        while ($row = $result->fetch_assoc()) {
            $marcas[] = $row;
        }

        $conexion->desconectar();
        return $marcas;
    }

    public function contarMarcas($search_query = '') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        if ($search_query) {
            $query = "SELECT COUNT(*) as total FROM marca WHERE nombreMarca LIKE '%$search_query%'";
        } else {
            $query = "SELECT COUNT(*) as total FROM marca";
        }
    
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
    
        return $row['total'];
    }
    

    public function cantidadMarcas(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM marca";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['total'];
    }

    /**
     * Get the value of idMarca
     */ 
    public function getIdMarca()
    {
        return $this->idMarca;
    }

    /**
     * Set the value of idMarca
     *
     * @return  self
     */ 
    public function setIdMarca($idMarca)
    {
        $this->idMarca = $idMarca;

        return $this;
    }

    /**
     * Get the value of nombreMarca
     */ 
    public function getNombreMarca()
    {
        return $this->nombreMarca;
    }

    /**
     * Set the value of nombreMarca
     *
     * @return  self
     */ 
    public function setNombreMarca($nombreMarca)
    {
        $this->nombreMarca = $nombreMarca;

        return $this;
    }
}
?>
