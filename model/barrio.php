<?php
require_once('conexion.php');
class Barrio{
    private $idBarrio;
    private $nombreBarrio;

    public function __construct($idBarrio=null, $nombreBarrio=null){
        $this->idBarrio = $idBarrio;
        $this->nombreBarrio = $nombreBarrio;
    }

    public function crearBarrio(){
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query="INSERT INTO barrio (nombreBarrio) VALUES ('$this->nombreBarrio')";
        if ($conn->query($query)==TRUE) {
            $conexion->desconectar();
            return true;
        }else {
            $conexion->desconectar();
            return false;
        }
    }

    public function listarBarrios(){
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query="SELECT * FROM barrio";
        $result=$conn->query($query);
        $barrios=[];
        while($row=$result->fetch_assoc()){
            $barrios[]=$row;
        }
        $conexion->desconectar();
        return $barrios;
    }

    public function eliminarBarrio($idBarrio){
        $conexion=new Conexion();
        $conn=$conexion->conectar();
        $query="DELETE FROM barrio WHERE idBarrio='$idBarrio'";
        $conn->query($query);
        $conexion->desconectar();
    }

    public function obtenerBarrioPorId($idBarrio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM barrio WHERE idBarrio='$idBarrio'";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $fila = $result->fetch_assoc();
            $conexion->desconectar();
            return new Barrio($fila['idBarrio'], $fila['nombreBarrio']);
        } else {
            $conexion->desconectar();
            return null;
        }
    }

    public function editarBarrio($idBarrio, $nombreBarrio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE barrio SET nombreBarrio='$nombreBarrio' WHERE idBarrio='$idBarrio'";
        $conn->query($query);
        $conexion->desconectar();
    }

    /**
     * Get the value of nombreBarrio
     */
    public function getNombreBarrio()
    {
        return $this->nombreBarrio;
    }

    /**
     * Set the value of nombreBarrio
     */
    public function setNombreBarrio($nombreBarrio): self
    {
        $this->nombreBarrio = $nombreBarrio;

        return $this;
    }

    /**
     * Get the value of idBarrio
     */
    public function getIdBarrio()
    {
        return $this->idBarrio;
    }

    /**
     * Set the value of idBarrio
     */
    public function setIdBarrio($idBarrio): self
    {
        $this->idBarrio = $idBarrio;

        return $this;
    }
}
?>