<?php
require_once 'conexion.php';

class PersonaDocumento {
    public $idPersonaDocumento;
    public $valor;
    public $persona_idPersona;
    public $tipoDocumento_idTipoDocumento;

    public function __construct($idPersonaDocumento = null, $valor = null, $persona_idPersona = null, $tipoDocumento_idTipoDocumento = null) {
        $this->idPersonaDocumento = $idPersonaDocumento;
        $this->valor = $valor;
        $this->persona_idPersona = $persona_idPersona;
        $this->tipoDocumento_idTipoDocumento = $tipoDocumento_idTipoDocumento;
    }

    public function crearPersonaDocumento() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO personaDocumento (valor, persona_idPersona, tipoDocumento_idTipoDocumento) 
                  VALUES ('$this->valor', '$this->persona_idPersona', '$this->tipoDocumento_idTipoDocumento')";
        $conn->query($query);
        $result = $conn->insert_id;
        $conexion->desconectar();
        return $result;
    }

    /**
     * Get the value of tipoDocumento_idTipoDocumento
     */ 
    public function getTipoDocumento_idTipoDocumento()
    {
        return $this->tipoDocumento_idTipoDocumento;
    }

    /**
     * Set the value of tipoDocumento_idTipoDocumento
     *
     * @return  self
     */ 
    public function setTipoDocumento_idTipoDocumento($tipoDocumento_idTipoDocumento)
    {
        $this->tipoDocumento_idTipoDocumento = $tipoDocumento_idTipoDocumento;

        return $this;
    }

    /**
     * Get the value of persona_idPersona
     */ 
    public function getPersona_idPersona()
    {
        return $this->persona_idPersona;
    }

    /**
     * Set the value of persona_idPersona
     *
     * @return  self
     */ 
    public function setPersona_idPersona($persona_idPersona)
    {
        $this->persona_idPersona = $persona_idPersona;

        return $this;
    }

    /**
     * Get the value of valor
     */ 
    public function getValor()
    {
        return $this->valor;
    }

    /**
     * Set the value of valor
     *
     * @return  self
     */ 
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get the value of idPersonaDocumento
     */ 
    public function getIdPersonaDocumento()
    {
        return $this->idPersonaDocumento;
    }

    /**
     * Set the value of idPersonaDocumento
     *
     * @return  self
     */ 
    public function setIdPersonaDocumento($idPersonaDocumento)
    {
        $this->idPersonaDocumento = $idPersonaDocumento;

        return $this;
    }
}
?>
