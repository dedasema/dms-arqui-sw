<?php

class NCarrera
{
    private $dCarrera;

    public function __construct()
    {
        $this->dCarrera = new DCarrera();
    }

    public function crearCarrera($nombre, $sigla)
    {
        return $this->dCarrera->crearCarrera($nombre, $sigla);
    }

    public function editarCarrera($id, $nombre, $sigla)
    {
        return $this->dCarrera->editarCarrera($id, $nombre, $sigla);
    }

    public function eliminarCarrera($id)
    {
        return $this->dCarrera->eliminarCarrera($id);
    }

    public function obtenerCarreras()
    {
        return $this->dCarrera->obtenerCarreras();
    }
}
