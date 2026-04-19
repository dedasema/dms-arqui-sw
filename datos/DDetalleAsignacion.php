<?php

class DDetalleAsignacion
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearAsignacion($usuario_id, $rol, $proyecto_id)
    {
        // ON CONFLICT DO NOTHING previene duplicados del par (proyecto_id, usuario_id)
        $sql = "INSERT INTO asignacion_proyecto (usuario_id, rol, proyecto_id) 
                VALUES (?, ?, ?) 
                ON CONFLICT (proyecto_id, usuario_id) DO NOTHING";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$usuario_id, $rol, $proyecto_id]);
    }

    public function eliminarAsignacion($proyecto_id)
    {
        $sql = "UPDATE asignacion_proyecto SET eliminado = TRUE WHERE proyecto_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$proyecto_id]);
    }

    public function obtenerAsignaciones($proyecto_id)
    {
        $sql = "SELECT a.*, u.nombre_completo, u.correo, u.codigo 
                FROM asignacion_proyecto a
                JOIN usuario u ON a.usuario_id = u.id
                WHERE a.proyecto_id = ? AND a.eliminado = FALSE
                ORDER BY a.id ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$proyecto_id]);
        return $stmt->fetchAll();
    }
}
