<?php

class DComentario
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function insertar($mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id)
    {
        $sql = "INSERT INTO comentario (mensaje, ruta_archivo, version_id, usuario_id, proyecto_id) VALUES (?, ?, ?, ?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id]);
        return $stmt->fetch();
    }

    public function obtenerComentarios($version_id)
    {
        $sql = "SELECT c.*, u.nombre_completo as nombre_usuario, u.rol as rol_usuario
                FROM comentario c
                JOIN usuario u ON c.usuario_id = u.id
                WHERE c.version_id = ?
                ORDER BY c.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$version_id]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT * FROM comentario WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
