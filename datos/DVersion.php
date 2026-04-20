<?php

class DVersion
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function obtenerUltimoNumero($proyecto_id)
    {
        $sql = "SELECT COALESCE(MAX(numero), 0) as ultimo FROM version_documental WHERE proyecto_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$proyecto_id]);
        $row = $stmt->fetch();
        return (int) ($row['ultimo'] ?? 0);
    }

    public function subir($nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id, $estado, $numero)
    {
        $sql = "INSERT INTO version_documental (nombre, peso_bytes, proyecto_id, ruta_archivo, usuario_id, estado, numero) 
                VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id, $estado, $numero]);
        return $stmt->fetch();
    }

    public function obtenerVersiones($proyecto_id)
    {
        $sql = "SELECT v.*, u.nombre_completo as nombre_usuario
                FROM version_documental v
                JOIN usuario u ON v.usuario_id = u.id
                WHERE v.proyecto_id = ?
                ORDER BY v.numero DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$proyecto_id]);
        return $stmt->fetchAll();
    }

    public function obtenerPorId($id)
    {
        $sql = "SELECT v.*, p.titulo as nombre_proyecto 
                FROM version_documental v
                JOIN proyecto p ON v.proyecto_id = p.id
                WHERE v.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
