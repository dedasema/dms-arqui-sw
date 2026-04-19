<?php

class DProyecto
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)
    {
        $carrera_id = (!empty($carrera_id) && $carrera_id > 0) ? $carrera_id : null;
        $sql = "INSERT INTO proyecto (titulo, estado, carrera_id, modalidad_id, gestion_id) VALUES (?, ?, ?, ?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$titulo, $estado, $carrera_id, $modalidad_id, $gestion_id]);
        return $stmt->fetch();
    }

    public function editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)
    {
        $carrera_id = (!empty($carrera_id) && $carrera_id > 0) ? $carrera_id : null;
        $sql = "UPDATE proyecto SET titulo = ?, estado = ?, carrera_id = ?, modalidad_id = ?, gestion_id = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$titulo, $estado, $carrera_id, $modalidad_id, $gestion_id, $id]);
    }

    public function eliminarProyecto($id)
    {
        $sql = "UPDATE proyecto SET eliminado = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerProyectos()
    {
        $sql = "SELECT p.*, c.nombre as nombre_carrera, m.nombre as nombre_modalidad, g.codigo as codigo_gestion 
                FROM proyecto p
                LEFT JOIN carrera c ON p.carrera_id = c.id
                JOIN modalidad_titulacion m ON p.modalidad_id = m.id
                JOIN gestion g ON p.gestion_id = g.id
                WHERE p.eliminado = FALSE ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function actualizarEstado($id, $estado)
    {
        $sql = "UPDATE proyecto SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }
}
