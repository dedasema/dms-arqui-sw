<?php

class DUsuario
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearUsuario($nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)
    {
        $carrera_id = (!empty($carrera_id) && $carrera_id > 0) ? $carrera_id : null;
        $sql = "INSERT INTO usuario (nombre_completo, correo, contrasena, rol, codigo, carrera_id) VALUES (?, ?, ?, ?, ?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $correo, $contrasena, $rol, $codigo, $carrera_id]);
        return $stmt->fetch();
    }

    public function editarUsuario($id, $nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)
    {
        $carrera_id = (!empty($carrera_id) && $carrera_id > 0) ? $carrera_id : null;
        $sql = "UPDATE usuario SET nombre_completo = ?, correo = ?, contrasena = ?, rol = ?, codigo = ?, carrera_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $correo, $contrasena, $rol, $codigo, $carrera_id, $id]);
    }

    public function eliminarUsuario($id)
    {
        $sql = "UPDATE usuario SET eliminado = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerUsuarios()
    {
        $sql = "SELECT u.*, c.nombre as nombre_carrera 
                FROM usuario u 
                LEFT JOIN carrera c ON u.carrera_id = c.id 
                WHERE u.eliminado = FALSE 
                ORDER BY u.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerContrasenaActual($id)
    {
        $sql = "SELECT contrasena FROM usuario WHERE id = ? AND eliminado = FALSE";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $row['contrasena'] : null;
    }

    public function obtenerPorCorreo($correo)
    {
        $sql = "SELECT id, nombre_completo, correo, contrasena, rol FROM usuario WHERE correo = ? AND eliminado = FALSE";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$correo]);
        return $stmt->fetch();
    }
}
