<?php
require_once('conexion.php');
class DomicilioDetalle {
    public $idDomicilio;
    public $idAtributo;
    public $valor;

    public function __construct($idDomicilio, $idAtributo, $valor) {
        $this->idDomicilio = $idDomicilio;
        $this->idAtributo = $idAtributo;
        $this->valor = $valor;
    }

    public function guardarDetalle() {
        $conexion = new Conexion();
        $conn=$conexion->conectar();
        $query="INSERT INTO domicilioDetalle (domicilio_idDomicilio, atributoDomicilio_idAtributoDomicilio, valor) VALUES ('$this->idDomicilio', '$this->idAtributo', '$this->valor') ON DUPLICATE KEY UPDATE valor = '$this->valor'";
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
}