<?php
require_once('conexion.php');

class AtributoDomicilio {
    public $idAtributoDomicilio;
    public $nombreAtributo;

    public function __construct($idAtributoDomicilio=null, $nombreAtributo=null){
        $this->idAtributoDomicilio = $idAtributoDomicilio;
        $this->nombreAtributo = $nombreAtributo;
    }

    public function listarAtributosDomicilio() {
        $conexion = new Conexion();
        $query = "SELECT idAtributoDomicilio, nombreAtributo FROM atributoDomicilio";
        $resultado = $conexion->consultar($query);
        $atributos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $atributos[] = $fila;
        }
        return $atributos;
    }
    
    
    
    

    public function obtenerAtributoPorId(){
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query = "SELECT idAtributoDomicilio, nombreAtributo FROM atributoDomicilio WHERE idAtributoDomicilio = '$this->idAtributoDomicilio'";
        if ($conn->query($query)==TRUE) {
            $conexion->desconectar();
            return true;
        }else {
            $conexion->desconectar();
            return false;
        }
    }

    public function agregarAtributoDomicilio() {
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query = "INSERT INTO atributoDomicilio (nombreAtributo) VALUES ('$this->nombreAtributo')";
        if ($conn->query($query)==TRUE) {
            $conexion->desconectar();
            return true;
        }else {
            $conexion->desconectar();
            return false;
        }
    }

    public function actualizarAtributoDomicilio() {
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query = "UPDATE atributoDomicilio SET nombreAtributo = '$this->nombreAtributo' WHERE idAtributoDomicilio = '$this->idAtributoDomicilio'";
        if ($conn->query($query)==TRUE) {
            $conexion->desconectar();
            return true;
        }else {
            $conexion->desconectar();
            return false;
        }
    }

    public function eliminarAtributoDomicilio($id) {
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query = "DELETE FROM atributoDomicilio WHERE idAtributoDomicilio = '$this->idAtributoDomicilio'";
        if ($conn->query($query)==TRUE) {
            $conexion->desconectar();
            return true;
        }else {
            $conexion->desconectar();
            return false;
        }
    }

    /**
     * Get the value of idAtributoDomicilio
     */ 
    public function getIdAtributoDomicilio()
    {
        return $this->idAtributoDomicilio;
    }

    /**
     * Set the value of idAtributoDomicilio
     *
     * @return  self
     */ 
    public function setIdAtributoDomicilio($idAtributoDomicilio)
    {
        $this->idAtributoDomicilio = $idAtributoDomicilio;

        return $this;
    }

    /**
     * Get the value of nombreAtributo
     */ 
    public function getNombreAtributo()
    {
        return $this->nombreAtributo;
    }

    /**
     * Set the value of nombreAtributo
     *
     * @return  self
     */ 
    public function setNombreAtributo($nombreAtributo)
    {
        $this->nombreAtributo = $nombreAtributo;

        return $this;
    }
}
?>
