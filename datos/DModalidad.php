<?php

class DModalidad
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearModalidad($nombre, $descripcion)
    {
        $sql = "INSERT INTO modalidad_titulacion (nombre, descripcion) VALUES (?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $descripcion]);
        return $stmt->fetch();
    }

    public function editarModalidad($id, $nombre, $descripcion)
    {
        $sql = "UPDATE modalidad_titulacion SET nombre = ?, descripcion = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $id]);
    }

    public function eliminarModalidad($id)
    {
        $sql = "UPDATE modalidad_titulacion SET eliminado = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerModalidades()
    {
        $sql = "SELECT id, nombre, descripcion FROM modalidad_titulacion WHERE eliminado = FALSE ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
