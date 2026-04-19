<?php

class NUsuario
{
    private $dUsuario;

    public function __construct()
    {
        $this->dUsuario = new DUsuario();
    }

    public function crearUsuario($nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)
    {
        return $this->dUsuario->crearUsuario($nombre, $correo, $contrasena, $rol, $codigo, $carrera_id);
    }

    public function editarUsuario($id, $nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)
    {
        return $this->dUsuario->editarUsuario($id, $nombre, $correo, $contrasena, $rol, $codigo, $carrera_id);
    }

    public function eliminarUsuario($id)
    {
        return $this->dUsuario->eliminarUsuario($id);
    }

    public function obtenerUsuarios()
    {
        return $this->dUsuario->obtenerUsuarios();
    }

    public function obtenerContrasenaActual($id)
    {
        return $this->dUsuario->obtenerContrasenaActual($id);
    }

    public function obtenerPorCorreo($correo)
    {
        return $this->dUsuario->obtenerPorCorreo($correo);
    }
}
