<?php
require_once('conexion.php');

class Domicilio{
    private $idAtributoDomicilio;
    private $nombreAtributo;
    private $barrio_idBarrio;
    private $persona_idPersona;

    public function __construct($idAtributoDomicilio=null, $nombreAtributo=null, $barrio_idBarrio=null, $persona_idPersona=null){
        $this->idAtributoDomicilio = $idAtributoDomicilio;
        $this->nombreAtributo = $nombreAtributo;
        $this->barrio_idBarrio = $barrio_idBarrio;
        $this->persona_idPersona = $persona_idPersona;
    }

    public function listarAtributosDomicilio(){
        $conexion = new Conexion();
        $query = "SELECT * FROM domicilio";
        $resultado = $conexion->consultar($query);
        $domicilios = [];
        while ($fila = $resultado->fetch_assoc()) {
            $domicilios[] = $fila;
        }
        return $domicilios;
    }    

    public function obtenerDomicilioPorId($idAtributoDomicilio){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM domicilio WHERE idAtributoDomicilio = '$idAtributoDomicilio'";
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    

    public function crearAtributoDomicilio($nombreAtributo, $idDomicilio) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $stmt = $conn->prepare("INSERT INTO atributoDomicilio (nombreAtributo) VALUES (?)");
        $stmt->bind_param("s", $nombreAtributo);
        $stmt->execute();
        $idAtributoDomicilio = $stmt->insert_id;
    
        $this->insertarAtributoDomicilio($idDomicilio, $idAtributoDomicilio, "");
        $stmt->close();
        $conn->close();
        return $idAtributoDomicilio;
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

    public function obtenerDetallesDomicilioPorPersona($personaId) {
        $conexion = new Conexion();
        $query = "
            SELECT dd.idDomicilioDetalle, dd.valor, ad.nombreAtributo 
            FROM domicilioDetalle dd
            JOIN atributoDomicilio ad ON dd.atributoDomicilio_idAtributoDomicilio = ad.idAtributoDomicilio
            JOIN domicilio d ON dd.domicilio_idDomicilio = d.idDomicilio
            WHERE d.persona_idPersona = $personaId
        ";
        $resultado = $conexion->consultar($query);
        $detalles = [];
        while ($fila = $resultado->fetch_assoc()) {
            $detalles[] = $fila;
        }
        return $detalles;
    }

    public function guardarDomicilio($personaId, $barrioId) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $stmt = $conn->prepare("INSERT INTO domicilio (persona_idPersona, barrio_idBarrio) VALUES (?, ?) ON DUPLICATE KEY UPDATE barrio_idBarrio = ?");
        $stmt->bind_param("iii", $personaId, $barrioId, $barrioId);
        $stmt->execute();
        return $conn->insert_id;
    }

    public function insertarAtributoDomicilio($idDomicilio, $idAtributo, $valor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $stmt = $conn->prepare("INSERT INTO domicilioDetalle (domicilio_idDomicilio, atributoDomicilio_idAtributoDomicilio, valor) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $idDomicilio, $idAtributo, $valor);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }    

    public function actualizarBarrio() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "UPDATE domicilio SET barrio_idBarrio = ? WHERE persona_idPersona = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $this->barrio_idBarrio, $this->persona_idPersona);
        $result = $stmt->execute();
        $stmt->close();
        $conexion->desconectar();
        return $result;
    }

    public function actualizarAtributoDomicilio($persona_idPersona, $nombreAtributo, $valor) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT idAtributoDomicilio FROM atributoDomicilio WHERE nombreAtributo = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $nombreAtributo);
        $stmt->execute();
        $stmt->bind_result($idAtributoDomicilio);
        $stmt->fetch();
        $stmt->close();
    
        if (!$idAtributoDomicilio) {
            $conexion->desconectar();
            return false; 
        }
    
        $query = "
            UPDATE domicilioDetalle dd
            JOIN domicilio d ON dd.domicilio_idDomicilio = d.idDomicilio
            SET dd.valor = ?
            WHERE d.persona_idPersona = ? AND dd.atributoDomicilio_idAtributoDomicilio = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sii', $valor, $persona_idPersona, $idAtributoDomicilio);
        $resultado = $stmt->execute();
    
        $stmt->close();
        $conexion->desconectar();
        return $resultado;
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

    /**
     * Get the value of Barrio_idBarrio
     */
    public function getBarrio_idBarrio()
    {
        return $this->Barrio_idBarrio;
    }

    /**
     * Set the value of Barrio_idBarrio
     */
    public function setBarrio_idBarrio($Barrio_idBarrio): self
    {
        $this->Barrio_idBarrio = $Barrio_idBarrio;

        return $this;
    }
    /**
     * Get the value of Persona_idPersona
     */
    public function getPersona_idPersona()
    {
        return $this->Persona_idPersona;
    }

    /**
     * Set the value of Persona_idPersona
     */
    public function setPersona_idPersona($Persona_idPersona): self
    {
        $this->Persona_idPersona = $Persona_idPersona;

        return $this;
    }
}
?>