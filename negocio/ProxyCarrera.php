<?php

require_once __DIR__ . '/ICarrera.php';
require_once __DIR__ . '/NCarrera.php';

class ProxyCarrera implements ICarrera
{
    private $realBusiness;

    public function __construct()
    {
        // El Proxy controla la instanciación del objeto real de negocio
        $this->realBusiness = new NCarrera();
    }

    private function verificarAutorizacion($rolesPermitidos = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 1. Verificación de Autenticación básica (session.php)
        if (!isset($_SESSION['usuario_id'])) {
            throw new Exception("UNAUTHENTICATED");
        }

        // 2. Verificación de Autorización por Rol
        if (!empty($rolesPermitidos) && !in_array($_SESSION['rol'], $rolesPermitidos)) {
            throw new Exception("UNAUTHORIZED");
        }
    }

    public function crearCarrera($nombre, $sigla)
    {
        $this->verificarAutorizacion(['Administrador']); 
        return $this->realBusiness->crearCarrera($nombre, $sigla);
    }

    public function editarCarrera($id, $nombre, $sigla)
    {
        $this->verificarAutorizacion(['Administrador']);
        return $this->realBusiness->editarCarrera($id, $nombre, $sigla);
    }

    public function eliminarCarrera($id)
    {
        $this->verificarAutorizacion(['Administrador']);
        return $this->realBusiness->eliminarCarrera($id);
    }

    public function obtenerCarreras()
    {
        $this->verificarAutorizacion(); // Permite el acceso a cualquier usuario autenticado
        return $this->realBusiness->obtenerCarreras();
    }
}