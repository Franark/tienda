<?php
require_once('conexion.php');

class Domicilio{
    private $idAtributoDomicilio;
    private $nombreAtributo;

    public function __construct($idAtributoDomicilio=null, $nombreAtributo=null){
        $this->idAtributoDomicilio = $idAtributoDomicilio;
        $this->nombreAtributo = $nombreAtributo;
    }

    public function listarAtributosDomicilio(){
        $conexion = new Conexion();
        $query = "SELECT * FROM atributoDomicilio";
        return $conexion->consultar($query);
    }

    public function obtenerAtributoDomicilioPorId($idAtributoDomicilio){
        $conexion = new Conexion();
        $query = "SELECT * FROM atributoDomicilio WHERE idAtributoDomicilio = $idAtributoDomicilio";
        $resultado = $conexion->consultar($query);
        return $resultado->fetch_assoc();
    }    

    public function crearAtributoDomicilio(){
        $conexion = new Conexion();
        $query = "INSERT INTO atributoDomicilio (nombreAtributo) VALUES ('$this->nombreAtributo')";
        return $conexion->consultar($query);
    }

    public function eliminarAtributoDomicilio($idDomicilio){
        $conexion = new Conexion();
        $query = "DELETE FROM atributoDomicilio WHERE idAtributoDomicilio = $idDomicilio";
        return $conexion->consultar($query);
    }

    public function editarAtributoDomicilio($idAtributoDomicilio){
        $conexion = new Conexion();
        $query = "UPDATE atributoDomicilio SET nombreAtributo = '$this->nombreAtributo' WHERE idAtributoDomicilio = $idAtributoDomicilio";
        return $conexion->consultar($query);
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
     */
    public function setIdAtributoDomicilio($idAtributoDomicilio): self
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
     */
    public function setNombreAtributo($nombreAtributo): self
    {
        $this->nombreAtributo = $nombreAtributo;

        return $this;
    }
}
?>