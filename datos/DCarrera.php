<?php

class DCarrera
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function crearCarrera($nombre, $sigla)
    {
        $sql = "INSERT INTO carrera (nombre, sigla) VALUES (?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$nombre, $sigla]);
        return $stmt->fetch();
    }

    public function editarCarrera($id, $nombre, $sigla)
    {
        $sql = "UPDATE carrera SET nombre = ?, sigla = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $sigla, $id]);
    }

    public function eliminarCarrera($id)
    {
        $sql = "UPDATE carrera SET eliminado = TRUE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function obtenerCarreras()
    {
        $sql = "SELECT id, nombre, sigla FROM carrera WHERE eliminado = FALSE ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
