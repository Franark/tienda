<?php
require_once("conexion.php");

class Usuario{
    public $id;
    public $nickname;
    public $email;
    public $password;
    public $rolUsuario_idRolUsuario;

    public function __construct($id = '', $nickname = '', $email = '', $password = '', $rolUsuario_idRolUsuario = '') {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->password = $password;
        $this->rolUsuario_idRolUsuario = $rolUsuario_idRolUsuario;
    }

    public function listarUsuarios() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT u.idUsuario, u.nickname, u.email, r.nombreRol 
                  FROM usuario u
                  JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario";
        $result = $conn->query($query);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }

        $conexion->desconectar();
        return $usuarios;
    }

    public function crearUsuario($nombrePersona, $apellidoPersona, $edadPersona, $tipoSexo_idTipoSexo) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $passwordHash = password_hash($this->nickname, PASSWORD_DEFAULT);

        $conn->begin_transaction();

        try {
            $queryUsuario = "INSERT INTO usuario (nickname, email, password, rolUsuario_idRolUsuario) VALUES ('$this->nickname', '$this->email', '$passwordHash', '$this->rolUsuario_idRolUsuario')";
            if ($conn->query($queryUsuario) === TRUE) {
                $idUsuario = $conn->insert_id;

                $queryPersona = "INSERT INTO persona (nombrePersona, apellidoPersona, edadPersona, tipoSexo_idTipoSexo, usuario_idUsuario) VALUES ('$nombrePersona', '$apellidoPersona', '$edadPersona', '$tipoSexo_idTipoSexo', '$this->id')";
                if ($conn->query($queryPersona) === TRUE) {
                    $conn->commit();
                    $conexion->desconectar();
                    return true;
                } else {
                    throw new Exception("Error al insertar en la tabla persona: " . $conn->error);
                }
            } else {
                throw new Exception("Error al insertar en la tabla usuario: " . $conn->error);
            }
        } catch (Exception $e) {
            $conn->rollback();
            $conexion->desconectar();
            return false;
        }
    }

    public function guardar() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO usuario (nickname, email, password, rolUsuario_idRolUsuario) VALUES ('$this->nickname', '$this->email', '$passwordHash', '$this->rolUsuario_idRolUsuario')";
        
        if ($conn->query($query) === TRUE) {
            $idUsuario = $conn->insert_id;
            
            $queryPersona = "INSERT INTO persona (usuario_idUsuario) VALUES ('$idUsuario')";
            if ($conn->query($queryPersona) === TRUE) {
                $conexion->desconectar();
                return true;
            } else {
                echo "Error en la inserción de persona: " . $conn->error;
            }
        } else {
            echo "Error en la inserción de usuario: " . $conn->error;
        }
        $conexion->desconectar();
        return false;
    }    

    public function obtenerUsuarioPorId($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM usuario WHERE idUsuario = '$id'";
        $result = $conn->query($query);
    
        $usuario = null;
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        }
    
        $conexion->desconectar();
        return $usuario;
    }

    public function crearContraseña() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET password = '$passwordHash' WHERE idUsuario = '$this->id'";
        
        if ($conn->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
    public function actualizar() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET nickname = '$this->nickname', email = '$this->email', password = '$passwordHash', rolUsuario_idRolUsuario = '$this->rolUsuario_idRolUsuario' WHERE idUsuario = '$this->id'";
        return $conexion->insertar($query);
    }

    public function eliminar() {
        $conexion = new Conexion();
        $query = "DELETE FROM usuario WHERE idUsuario = '$this->id'";
        
        return $conexion->insertar($query);
    }

    public function login($nickname, $password) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT * FROM usuario WHERE nickname = '$nickname'";
        $result = $conn->query($query);
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->id = $user['idUsuario'];
            if (password_verify($password, $user['password'])) {
                return true;
            }
        }
    }
    

    public function getRolUsuarioId($nickname) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT rolUsuario_idRolUsuario FROM usuario WHERE nickname = '$nickname'";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['rolUsuario_idRolUsuario'];
        }
        $conexion->desconectar();
        return null;
    }


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getNickname() {
        return $this->nickname;
    }

    public function setNickname($nickname) {
        $this->nickname = $nickname;
        return $this;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function getRolUsuarioIdRolUsuario() {
        return $this->rolUsuario_idRolUsuario;
    }

    public function setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario) {
        $this->rolUsuario_idRolUsuario = $rolUsuario_idRolUsuario;
        return $this;
    }
}
?>