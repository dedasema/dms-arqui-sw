<?php

class NModalidad
{
    private $dModalidad;

    public function __construct()
    {
        $this->dModalidad = new DModalidad();
    }

    public function crearModalidad($nombre, $descripcion)
    {
        return $this->dModalidad->crearModalidad($nombre, $descripcion);
    }

    public function editarModalidad($id, $nombre, $descripcion)
    {
        return $this->dModalidad->editarModalidad($id, $nombre, $descripcion);
    }

    public function eliminarModalidad($id)
    {
        return $this->dModalidad->eliminarModalidad($id);
    }

    public function obtenerModalidades()
    {
        return $this->dModalidad->obtenerModalidades();
    }
}
