<?php
require_once 'conexion.php';

class RolPermiso {
    private $idRolPermisos;
    private $rolUsuario_idRolUsuario;
    private $permiso_idPermiso;

    public function listarRolPermisos($idRolUsuario) {
        if (empty($idRolUsuario)) {
            return [];
        }
    
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT p.nombrePermiso 
                  FROM permiso p
                  JOIN rolPermisos rp ON p.idPermiso = rp.permiso_idPermiso
                  WHERE rp.rolUsuario_idRolUsuario = $idRolUsuario";
        
        $resultado = $conn->query($query);
        $permisos = [];
    
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $permisos[] = $row['nombrePermiso'];
            }
        }
    
        $conexion->desconectar();
        return $permisos;
    }    


    public function agregarRolPermiso($rol_idRol, $permiso_idPermiso) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "INSERT INTO rolPermisos (rolUsuario_idRolUsuario, permiso_idPermiso) 
                  VALUES ($rol_idRol, $permiso_idPermiso)";
        
        if ($conn->query($query) === TRUE) {
            $id = $conn->insert_id;
        } else {
            $id = 0;
        }

        $conexion->desconectar();
        return $id;
    }

    public function eliminarRolPermiso($rol_idRol) {
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "DELETE FROM rolPermisos WHERE rolUsuario_idRolUsuario = $rol_idRol";

        $result = $conn->query($query);
        $conexion->desconectar();
        return $result;
    }

    public function obtenerPermisosPorRol($idRolUsuario) {
        if (empty($idRolUsuario)) {
            return [];
        }
    
        $conexion = new Conexion();
        $conn = $conexion->conectar();
        $query = "SELECT permiso_idPermiso 
                  FROM rolPermisos 
                  WHERE rolUsuario_idRolUsuario = $idRolUsuario";
        
        $resultado = $conn->query($query);
        $permisos = [];
    
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $permisos[] = $row['permiso_idPermiso'];
            }
        }
    
        $conexion->desconectar();
        return $permisos;
    }
    
    /**
     * Get the value of idRolPermisos
     */
    public function getIdRolPermisos()
    {
        return $this->idRolPermisos;
    }

    /**
     * Set the value of idRolPermisos
     */
    public function setIdRolPermisos($idRolPermisos): self
    {
        $this->idRolPermisos = $idRolPermisos;

        return $this;
    }

    /**
     * Get the value of rolUsuario_idRolUsuario
     */
    public function getRolUsuarioIdRolUsuario()
    {
        return $this->rolUsuario_idRolUsuario;
    }

    /**
     * Set the value of rolUsuario_idRolUsuario
     */
    public function setRolUsuarioIdRolUsuario($rolUsuario_idRolUsuario): self
    {
        $this->rolUsuario_idRolUsuario = $rolUsuario_idRolUsuario;

        return $this;
    }

    /**
     * Get the value of permiso_idPermiso
     */
    public function getPermisoIdPermiso()
    {
        return $this->permiso_idPermiso;
    }

    /**
     * Set the value of permiso_idPermiso
     */
    public function setPermisoIdPermiso($permiso_idPermiso): self
    {
        $this->permiso_idPermiso = $permiso_idPermiso;

        return $this;
    }
}
?>