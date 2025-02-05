<?php
require_once ('conexion.php');
require_once ('persona.php');

class Cliente {
    private $idCliente;
    private $cliente_idCliente;

    public function __construct($idCliente = null, $cliente_idCliente = null) {
        $this->idCliente = $idCliente;
        $this->cliente_idCliente = $cliente_idCliente;
    }
    public function listarClientes() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $query = "SELECT 
                    c.idCliente, 
                    p.idPersona, 
                    p.nombrePersona, 
                    p.apellidoPersona 
                FROM cliente c
                JOIN persona p ON c.cliente_idCliente = p.idPersona";

        $result = $conn->query($query);

        $clientes = [];
        while ($row = $result->fetch_assoc()) {
            $clientes[] = [
                'idCliente' => $row['idCliente'],
                'idPersona' => $row['idPersona'],
                'nombrePersona' => $row['nombrePersona'],
                'apellidoPersona' => $row['apellidoPersona'],
            ];
        }

        $conexion->desconectar();
        return $clientes;
    }
    public function crearCliente($personaId) {
    $conexion = new Conexion();
    $conn = $conexion->conectar();
    $query = "INSERT INTO cliente (persona_idPersona) VALUES ('$personaId')";

    if ($conn->query($query) === TRUE) {
        $this->idCliente = $conn->insert_id;
        $conexion->desconectar();
        return true;
    } else {
        $conexion->desconectar();
        return false;
    }
}
public function obtenerClientePorId($idCliente) {
    $conexion = new Conexion();
    $conn = $conexion->conectar();

    $query = "SELECT 
                c.idCliente, 
                p.idPersona, 
                p.nombrePersona, 
                p.apellidoPersona,
                u.email AS correoElectronico
              FROM cliente c
              JOIN persona p ON c.persona_idPersona = p.idPersona
              JOIN usuario u ON u.idUsuario = p.usuario_idUsuario
              WHERE c.idCliente = '$idCliente'";

    $result = $conn->query($query);
    $cliente = null;

    if ($row = $result->fetch_assoc()) {
        $cliente = [
            'idCliente' => $row['idCliente'],
            'idPersona' => $row['idPersona'],
            'nombre' => $row['nombrePersona'],
            'apellido' => $row['apellidoPersona'],
            'correoElectronico' => $row['correoElectronico']
        ];
    }
    $conexion->desconectar();
    return $cliente;
}


    public function actualizarCliente($idCliente, $personaId) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();

        $query = "UPDATE cliente 
                  SET cliente_idCliente = '$personaId' 
                  WHERE idCliente = '$idCliente'";

        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    
    public function getIdCliente() {
        return $this->idCliente;
    }

    public function setIdCliente($idCliente) {
        $this->idCliente = $idCliente;
    }

    public function getClienteIdCliente() {
        return $this->cliente_idCliente;
    }

    public function setClienteIdCliente($cliente_idCliente) {
        $this->cliente_idCliente = $cliente_idCliente;
    }
}
?>