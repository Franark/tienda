<?php
require_once('conexion.php');
class Permiso{
    private $idPermiso;
    private $nombrePermiso;
    public function __construct($idPermiso=null , $nombrePermiso=null){
        $this->idPermiso = $idPermiso;
        $this->nombrePermiso = $nombrePermiso;
    }

    public function listarPermisos(){
        $conexion = new Conexion();
        $query = "SELECT * FROM permiso";
        return $conexion->consultar($query);
    }

    /**
     * Get the value of nombrePermiso
     */
    public function getNombrePermiso()
    {
        return $this->nombrePermiso;
    }

    /**
     * Set the value of nombrePermiso
     */
    public function setNombrePermiso($nombrePermiso): self
    {
        $this->nombrePermiso = $nombrePermiso;

        return $this;
    }

    /**
     * Get the value of idPermiso
     */
    public function getIdPermiso()
    {
        return $this->idPermiso;
    }

    /**
     * Set the value of idPermiso
     */
    public function setIdPermiso($idPermiso): self
    {
        $this->idPermiso = $idPermiso;

        return $this;
    }
}
?>