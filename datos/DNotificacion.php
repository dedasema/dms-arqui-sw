<?php

class DNotificacion
{
    private $conn;

    public function __construct()
    {
        $this->conn = DatabaseHelper::getInstance()->getConnection();
    }

    public function insertar($usuario_id, $mensaje)
    {
        $sql = "INSERT INTO notificacion (usuario_id, mensaje) VALUES (?, ?) RETURNING id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id, $mensaje]);
        return $stmt->fetch();
    }
}
