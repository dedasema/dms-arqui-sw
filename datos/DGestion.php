<?php

class DGestion
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearGestion($codigo, $fecha_inicio, $fecha_fin)
    {
        $fecha_fin = !empty($fecha_fin) ? $fecha_fin : null;
        $sql = "INSERT INTO gestion (codigo, fecha_inicio, fecha_fin) VALUES (?, ?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$codigo, $fecha_inicio, $fecha_fin]);
        return $stmt->fetch();
    }

    public function editarGestion($id, $codigo, $fecha_inicio, $fecha_fin)
    {
        $fecha_fin = !empty($fecha_fin) ? $fecha_fin : null;
        $sql = "UPDATE gestion SET codigo = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$codigo, $fecha_inicio, $fecha_fin, $id]);
    }

    public function eliminarGestion($id)
    {
        $sql = "UPDATE gestion SET eliminado = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerGestiones()
    {
        $sql = "SELECT id, codigo, fecha_inicio, fecha_fin FROM gestion WHERE eliminado = FALSE ORDER BY fecha_inicio DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
