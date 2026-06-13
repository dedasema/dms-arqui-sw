<?php

require_once __DIR__ . '/ICarreraBusiness.php';

class NCarrera implements ICarreraBusiness
{
    private $dCarrera;

    public function __construct()
    {
        // Se asume que DCarrera se carga mediante el autoload o en la vista principal
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