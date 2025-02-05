<?php
class Conexion {
    private $conn;
    private $server;
    private $username;
    private $password;
    private $db;

    public function __construct() {
        $this->server = "localhost";
        $this->username = "root";
        $this->password = "";
        $this->db = "tienda";
    }

    public function conectar() {
        try {
            $this->conn = new mysqli($this->server, $this->username, $this->password, $this->db);
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            return $this->conn;
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
    

    public function desconectar() {
        return $this->conn->close();   
    }

    public function insertar($query) {
        $conn = $this->conectar();
        if ($conn->query($query) === TRUE) {
            $id = $conn->insert_id;
        } else {
            $id = 0;
        }
        $this->desconectar();
        return $id;
    }

    public function consultar($query){
        $this->conectar();
        $resultado=$this->conn->query($query);
        $this->desconectar();
        return $resultado;
    }
}
?>
