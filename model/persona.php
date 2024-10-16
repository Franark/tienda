<?php
require_once 'conexion.php';

class Persona {
    private $idPersona;
    private $nombrePersona;
    private $apellidoPersona;
    private $edadPersona;
    private $nombreBarrio;
    private $tipoSexo_idTipoSexo;
    private $valorDocumento;
    private $tipoDocumento;
    private $contactos;
    private $domicilio;
    private $domicilioDetalle;

    public function __construct($idPersona=null ,$nombrePersona = null, $apellidoPersona = null, $edadPersona = null, $nombreBarrio = null, $tipoSexo_idTipoSexo=null, $valorDocumento=null, $tipoDocumento=null, $contactos=[], $domicilio=null, $domicilioDetalle=null) {
        $this->idPersona = $idPersona;
        $this->nombrePersona = $nombrePersona;
        $this->apellidoPersona = $apellidoPersona;
        $this->edadPersona = $edadPersona;
        $this->nombreBarrio = $nombreBarrio;
        $this->tipoSexo_idTipoSexo = $tipoSexo_idTipoSexo;
        $this->valorDocumento = $valorDocumento;
        $this->tipoDocumento = $tipoDocumento;
        $this->contactos = $contactos;
        $this->domicilio = $domicilio;
        $this->domicilioDetalle = $domicilioDetalle;
    }

    public function listarPersona($idUsuario) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $query = "SELECT 
                    p.idPersona, 
                    p.nombrePersona, 
                    p.apellidoPersona, 
                    p.edadPersona, 
                    p.tipoSexo_idTipoSexo,
                    pd.valor AS valorDocumento,
                    u.idUsuario,
                    pc.valor,
                    pc.tipoContacto_idTipoContacto,
                    pd.tipoDocumento_idTipoDocumento
                    
                FROM persona p 
                JOIN usuario u ON p.usuario_idUsuario = u.idUsuario 
                LEFT JOIN personaDocumento pd ON p.idPersona = pd.persona_idPersona
                LEFT JOIN personaContacto pc ON p.idPersona = pc.persona_idPersona
                WHERE u.idUsuario = $idUsuario";
        
        $result = $conn->query($query);

        $personas = [];
        while ($row = $result->fetch_assoc()) {
            $idPersona = $row['idPersona'];
            if (!isset($personas[$idPersona])) {
                $personas[$idPersona] = [
                    'idPersona' => $row['idPersona'],
                    'nombrePersona' => $row['nombrePersona'],
                    'apellidoPersona' => $row['apellidoPersona'],
                    'edadPersona' => $row['edadPersona'],
                    'tipoSexo_idTipoSexo' => $row['tipoSexo_idTipoSexo'],
                    'valorDocumento' => $row['valorDocumento'],
                    'idTipoDocumento' => $row['tipoDocumento_idTipoDocumento'],
                    'contactos' => []
                ];
            }
            $personas[$idPersona]['contactos'][] = [
                'valor' => $row['valor'],
                'tipoContacto_idTipoContacto' => $row['tipoContacto_idTipoContacto']
            ];
        }

        $conexion->desconectar();
        return array_values($personas);
    }

    public function actualizarPersona() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        // Actualizar los datos de la persona
        $query = "UPDATE persona 
                  SET nombrePersona = '$this->nombrePersona', 
                      apellidoPersona = '$this->apellidoPersona', 
                      edadPersona = '$this->edadPersona', 
                      tipoSexo_idTipoSexo = '$this->tipoSexo_idTipoSexo' 
                  WHERE idPersona = '$this->idPersona'";
        
        if ($conn->query($query) === TRUE) {
            
            $queryCheck = "SELECT COUNT(*) as count FROM personaDocumento WHERE persona_idPersona = '$this->idPersona'";
            $resultCheck = $conn->query($queryCheck);
            $rowCheck = $resultCheck->fetch_assoc();
    
            if ($rowCheck['count'] > 0) {
                $queryDocumento = "UPDATE personaDocumento 
                                   SET valor = '$this->valorDocumento' 
                                   WHERE persona_idPersona = '$this->idPersona' AND tipoDocumento_idTipoDocumento='$this->tipoDocumento'";
            } else {
                $queryDocumento = "INSERT INTO personaDocumento (valor ,persona_idPersona, tipoDocumento_idTipoDocumento) 
                                   VALUES ('$this->valorDocumento', '$this->idPersona', '$this->tipoDocumento')";
            }
    
            $conn->query($queryDocumento);
    
            $queryDeleteContactos = "DELETE FROM personaContacto WHERE persona_idPersona = '$this->idPersona'";
            $conn->query($queryDeleteContactos);
    
            foreach ($this->contactos as $contacto) {
                $queryInsertContacto = "INSERT INTO personaContacto (valor, persona_idPersona, tipoContacto_idTipoContacto) 
                                        VALUES ('{$contacto['valor']}', '$this->idPersona', '{$contacto['tipoContacto_idTipoContacto']}')";
                $conn->query($queryInsertContacto);
            }

            if (!empty($this->domicilio)) {
                $queryCheckDomicilio = "SELECT COUNT(*) as count FROM domicilio WHERE persona_idPersona = '$this->idPersona'";
                $resultCheckDomicilio = $conn->query($queryCheckDomicilio);
                $rowCheckDomicilio = $resultCheckDomicilio->fetch_assoc();
    
                if ($rowCheckDomicilio['count'] > 0) {
                    $queryDomicilio = "UPDATE domicilio 
                                       SET nombreBarrio = '{$this->domicilio['nombreBarrio']}', 
                                           calle = '{$this->domicilio['calle']}', 
                                           numero = '{$this->domicilio['numero']}', 
                                           tipoDomicilio = '{$this->domicilio['tipoDomicilio']}' 
                                       WHERE persona_idPersona = '$this->idPersona'";
                } else {
                    $queryDomicilio = "INSERT INTO domicilio (nombreBarrio, calle, numero, tipoDomicilio, persona_idPersona) 
                                       VALUES ('{$this->domicilio['nombreBarrio']}', '{$this->domicilio['calle']}', 
                                               '{$this->domicilio['numero']}', '{$this->domicilio['tipoDomicilio']}', 
                                               '$this->idPersona')";
                }
                $conn->query($queryDomicilio);
            }
    
            if (!empty($this->domicilioDetalle)) {
                $queryDeleteDomicilioDetalle = "DELETE FROM domicilio_detalle WHERE domicilio_idDomicilio = 
                                                (SELECT idDomicilio FROM domicilio WHERE persona_idPersona = '$this->idPersona')";
                $conn->query($queryDeleteDomicilioDetalle);
    
                foreach ($this->domicilioDetalle as $detalle) {
                    $queryInsertDomicilioDetalle = "INSERT INTO domicilio_detalle (detalle, domicilio_idDomicilio) 
                                                    VALUES ('{$detalle['detalle']}', 
                                                            (SELECT idDomicilio FROM domicilio WHERE persona_idPersona = '$this->idPersona'))";
                    $conn->query($queryInsertDomicilioDetalle);
                }
            }
    
            $conexion->desconectar();
            return true;
    
        } else {
            $conexion->desconectar();
            return false;
        }
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
     *
     * @return  self
     */ 
    public function setNombreBarrio($nombreBarrio)
    {
        $this->nombreBarrio = $nombreBarrio;

        return $this;
    }

    /**
     * Get the value of edadPersona
     */ 
    public function getEdadPersona()
    {
        return $this->edadPersona;
    }

    /**
     * Set the value of edadPersona
     *
     * @return  self
     */ 
    public function setEdadPersona($edadPersona)
    {
        $this->edadPersona = $edadPersona;

        return $this;
    }

    /**
     * Get the value of apellidoPersona
     */ 
    public function getApellidoPersona()
    {
        return $this->apellidoPersona;
    }

    /**
     * Set the value of apellidoPersona
     *
     * @return  self
     */ 
    public function setApellidoPersona($apellidoPersona)
    {
        $this->apellidoPersona = $apellidoPersona;

        return $this;
    }

    /**
     * Get the value of nombrePersona
     */ 
    public function getNombrePersona()
    {
        return $this->nombrePersona;
    }

    /**
     * Set the value of nombrePersona
     *
     * @return  self
     */ 
    public function setNombrePersona($nombrePersona)
    {
        $this->nombrePersona = $nombrePersona;

        return $this;
    }

    /**
     * Get the value of idPersona
     */ 
    public function getIdPersona()
    {
        return $this->idPersona;
    }

    /**
     * Set the value of idPersona
     *
     * @return  self
     */ 
    public function setIdPersona($idPersona)
    {
        $this->idPersona = $idPersona;

        return $this;
    }

    /**
     * Get the value of tipoSexo_idTipoSexo
     */ 
    public function getTipoSexo_idTipoSexo()
    {
        return $this->tipoSexo_idTipoSexo;
    }

    /**
     * Set the value of tipoSexo_idTipoSexo
     *
     * @return  self
     */ 
    public function setTipoSexo_idTipoSexo($tipoSexo_idTipoSexo)
    {
        $this->tipoSexo_idTipoSexo = $tipoSexo_idTipoSexo;

        return $this;
    }

    /**
     * Get the value of valorDocumento
     */ 
    public function getValorDocumento()
    {
        return $this->valorDocumento;
    }

    /**
     * Set the value of valorDocumento
     *
     * @return  self
     */ 
    public function setValorDocumento($valorDocumento)
    {
        $this->valorDocumento = $valorDocumento;

        return $this;
    }

    /**
     * Get the value of tipoDocumento
     */ 
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set the value of tipoDocumento
     *
     * @return  self
     */ 
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get the value of contactos
     */ 
    public function getContactos()
    {
        return $this->contactos;
    }

    /**
     * Set the value of contactos
     *
     * @return  self
     */ 
    public function setContactos($contactos)
    {
        $this->contactos = $contactos;

        return $this;
    }
}
?>