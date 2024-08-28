<?php
require_once 'conexion.php';

class Marca {
    public $idMarca;
    public $nombreMarca;

    public function __construct($idMarca = null, $nombreMarca = null) {
        $this->idMarca = $idMarca;
        $this->nombreMarca = $nombreMarca;
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
            error_log("Error en la creación de categoría: " . $conn->error);
            $conexion->desconectar();
            return false;
        }
    }

    public function actualizarMarca() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE marca SET nombreMarca='$this->nombreMarca' WHERE idMarca='$this->idMarca'";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function eliminarMarca($idMarca) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM marca WHERE idMarca='$idMarca'";
        $conn->query($query);
        $conexion->desconectar();
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
