<?php

require_once __DIR__ . '/ICarrera.php';
require_once __DIR__ . '/NCarrera.php';

class ProxyCarrera implements ICarrera
{
    private $realCarrera;

    public function __construct()
    {
        $this->realCarrera = new NCarrera();
    }

    private function verificarAutorizacion($rolesPermitidos = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("UNAUTHENTICATED");
        }

        if (!empty($rolesPermitidos) && !in_array($_SESSION['rol'], $rolesPermitidos)) {
            throw new Exception("UNAUTHORIZED");
        }
    }

    public function crearCarrera($nombre, $sigla)
    {
        $this->verificarAutorizacion(['Administrador']); 
        return $this->realCarrera->crearCarrera($nombre, $sigla);
    }

    public function editarCarrera($id, $nombre, $sigla)
    {
        $this->verificarAutorizacion(['Administrador']);
        return $this->realCarrera->editarCarrera($id, $nombre, $sigla);
    }

    public function eliminarCarrera($id)
    {
        $this->verificarAutorizacion(['Administrador']);
        return $this->realCarrera->eliminarCarrera($id);
    }

    public function obtenerCarreras()
    {
        $this->verificarAutorizacion(); 
        return $this->realCarrera->obtenerCarreras();
    }
}