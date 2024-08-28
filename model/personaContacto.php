<?php
require_once 'conexion.php';

class PersonaContacto {
    public $idPersonaContacto;
    public $valor;
    public $tipoContacto_idTipoContacto;
    public $persona_idPersona;

    public function __construct($valor = null, $tipoContacto_idTipoContacto = null, $persona_idPersona = null) {
        $this->valor = $valor;
        $this->tipoContacto_idTipoContacto = $tipoContacto_idTipoContacto;
        $this->persona_idPersona = $persona_idPersona;
    }

    public function crearPersonaContacto() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO personaContacto (valor, tipoContacto_idTipoContacto, persona_idPersona) 
                  VALUES ('$this->valor', '$this->tipoContacto_idTipoContacto', '$this->persona_idPersona')";
        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
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
     * Get the value of tipoContacto_idTipoContacto
     */ 
    public function getTipoContacto_idTipoContacto()
    {
        return $this->tipoContacto_idTipoContacto;
    }

    /**
     * Set the value of tipoContacto_idTipoContacto
     *
     * @return  self
     */ 
    public function setTipoContacto_idTipoContacto($tipoContacto_idTipoContacto)
    {
        $this->tipoContacto_idTipoContacto = $tipoContacto_idTipoContacto;

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
}
?>
