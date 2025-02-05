<?php
require_once("conexion.php");
require_once("paginacion.php");

class Usuario{
    public $id;
    public $nickname;
    public $email;
    public $password;
    public $rolUsuario_idRolUsuario;
    public $confirmacion;

    public function __construct($id = '', $nickname = '', $email = '', $password = '', $rolUsuario_idRolUsuario = '', $confirmacion = 0) {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->email = $email;
        $this->password = $password;
        $this->rolUsuario_idRolUsuario = $rolUsuario_idRolUsuario;
        $this->confirmacion = $confirmacion;
    }    

    public function listarUsuarios($current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $offset = ($current_page - 1) * $page_size;
        $query = "SELECT u.idUsuario, u.nickname, u.email, r.nombreRol FROM usuario u JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario LIMIT $offset, $page_size";
        
        $result = $conn->query($query);
        $usuarios = $result->fetch_all(MYSQLI_ASSOC);
        
        $conexion->desconectar();
        return $usuarios;
    }
    
    

    public function buscarUsuarios($search_query, $current_page, $page_size) {
        $offset = ($current_page - 1) * $page_size;
    
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        $query = "SELECT u.idUsuario, u.nickname, u.email, r.nombreRol
                  FROM usuario u
                  JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario
                  WHERE u.nickname LIKE ? OR u.email LIKE ?
                  LIMIT ?, ?";
        $stmt = $conn->prepare($query);
        $search = "%$search_query%";
        $stmt->bind_param('ssii', $search, $search, $offset, $page_size);
        $stmt->execute();
    
        $result = $stmt->get_result();
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    
        $conexion->desconectar();
        return $usuarios;
    }    

    public function listarUsuariosPorRol($rolId) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
    
        // Consulta para obtener todos los usuarios por rol
        $query = "SELECT u.idUsuario, u.nickname, u.email, r.nombreRol 
                  FROM usuario u
                  JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario
                  WHERE u.rolUsuario_idRolUsuario = ?";
    
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $rolId); // El rol es un entero
        $stmt->execute();
        $result = $stmt->get_result();
    
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    
        $conexion->desconectar();
        return $usuarios;
    }
    

    public function buscarUsuariosPorRol($search_query, $roleFilter, $current_page, $page_size) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT u.idUsuario, u.nickname, u.email, r.nombreRol 
                  FROM usuario u
                  JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario
                  WHERE u.nickname LIKE ? AND u.rolUsuario_idRolUsuario = ?
                  LIMIT ?, ?";
    
        $stmt = $conn->prepare($query);
        $search_query = "%$search_query%"; // Agregar comodín para la búsqueda parcial
        $stmt->bind_param("ssii", $search_query, $roleFilter, $current_page, $page_size); 
        $stmt->execute();
        $result = $stmt->get_result();
    
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    
        $conexion->desconectar();
        return $usuarios;
    }
    
    

    public function contarUsuariosPorRol($search_query, $roleFilter) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT COUNT(*) as totalUsuarios 
                  FROM usuario u
                  JOIN rolUsuario r ON u.rolUsuario_idRolUsuario = r.idRolUsuario
                  WHERE u.nickname LIKE ? AND u.rolUsuario_idRolUsuario = ?";
    
        $stmt = $conn->prepare($query);
        $search_query = "%$search_query%"; 
        $stmt->bind_param("ss", $search_query, $roleFilter); 
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    
        $conexion->desconectar();
        return $row['totalUsuarios'];
    }
    
    


    public function contarUsuarios($search_query = '') {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT COUNT(*) as total FROM usuario WHERE 1=1";
        
        if ($search_query) {
            $query .= " AND nickname LIKE '%$search_query%'";
        }
        
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['total'];
    }
    
    public function cantidadUsuarios(){
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as total FROM usuario WHERE confirmacion = 1";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['total'];
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
                $idPersona=$conn->insert_id;
                $queryCliente="INSERT INTO cliente (persona_idPersona) VALUES ($idPersona)";
                if ($conn->query($queryCliente) === TRUE) {
                    return true;
                } else {
                    echo "Error en la inserción de cliente: ". $conn->error;
                }
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
    $query = "SELECT u.*, c.idCliente
              FROM usuario u
              LEFT JOIN persona p ON u.idUsuario = p.usuario_idUsuario
              LEFT JOIN cliente c ON p.idPersona = c.persona_idPersona
              WHERE u.idUsuario = '$id'";

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
        $query = "UPDATE usuario SET nickname = ?, email = ?, rolUsuario_idRolUsuario = ? WHERE idUsuario = ?";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssii', $this->nickname, $this->email, $this->rolUsuario_idRolUsuario, $this->id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error al actualizar el usuario: " . $stmt->error);
            return false;
        }
    }    

    public function eliminar($id) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $queryPersona = "DELETE FROM persona WHERE usuario_idUsuario = '$id'";
        if ($conn->query($queryPersona) === TRUE) {
            $queryUsuario = "DELETE FROM usuario WHERE idUsuario = '$id'";
            if ($conn->query($queryUsuario) === TRUE) {
                $conexion->desconectar();
                return true;
            } else {
                $conexion->desconectar();
                return false;
            }
        } else {
            $conexion->desconectar();
            return false;
        }
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
                if ($user['confirmacion'] == 1) {
                    return true;
                } elseif ($user['confirmacion'] == 0) {
                    header('Location: ../?page=login&error=Por favor confirma tu cuenta antes de iniciar sesión.');
                    exit();
                }
            } else {
                header('Location: ../?page=login&error=Contraseña incorrecta');
                exit();
            }
        } else {
            header('Location: ../?page=login&error=Usuario no encontrado');
            exit();
        }
    }    
    
    public function guardarTokenConfirmacion($token) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE usuario SET token_confirmacion = '$token' WHERE email = '$this->email'";
        
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }
    
    public function verificarToken($token, $email) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT * FROM usuario WHERE email = '$email' AND token_confirmacion = '$token'";
        $result = $conn->query($query);
        
        $conexion->desconectar();
        
        return $result->num_rows > 0;
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

    public function buscarPorNickname($nickname) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as count FROM usuario WHERE nickname = '$nickname'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['count'] > 0;
    }
    
    public function buscarPorEmail($email) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT COUNT(*) as count FROM usuario WHERE email = '$email'";
        $result = $conn->query($query);
        $row = $result->fetch_assoc();
        $conexion->desconectar();
        return $row['count'] > 0;
    }    

    public function activarCuenta() {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "UPDATE usuario SET token_confirmacion = NULL WHERE email = '$this->email'";
        
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
    }

    public function obtenerUsuarioPorEmail($email) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT * FROM usuario WHERE email = '$email'";
        $result = $conn->query($query);
    
        $usuario = null;
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
        }
    
        $conexion->desconectar();
        return $usuario;
    }
    
    public function confirmarCuenta($email, $token) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        
        $query = "SELECT * FROM usuario WHERE email = '$email' AND token_confirmacion = '$token'";
        $result = $conn->query($query);
    
        if ($result->num_rows > 0) {
            $queryUpdate = "UPDATE usuario SET confirmacion = 1, token_confirmacion = NULL WHERE email = '$email'";
            
            if ($conn->query($queryUpdate) === TRUE) {
                $conexion->desconectar();
                return true;
            }
        }
        
        $conexion->desconectar();
        return false;
    }          

    public function restablecerContraseña($email, $nuevaContraseña) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $passwordHash = password_hash($nuevaContraseña, PASSWORD_DEFAULT);
    
        $query = "UPDATE usuario SET password = '$passwordHash', token_confirmacion = NULL WHERE email = '$email'";
    
        if ($conn->query($query) === TRUE) {
            $conexion->desconectar();
            return true;
        } else {
            $conexion->desconectar();
            return false;
        }
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