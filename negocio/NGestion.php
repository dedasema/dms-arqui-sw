<?php

class NGestion
{
    private $dGestion;

    public function __construct()
    {
        $this->dGestion = new DGestion();
    }

    public function crearGestion($codigo, $fecha_inicio, $fecha_fin)
    {
        return $this->dGestion->crearGestion($codigo, $fecha_inicio, $fecha_fin);
    }

    public function editarGestion($id, $codigo, $fecha_inicio, $fecha_fin)
    {
        return $this->dGestion->editarGestion($id, $codigo, $fecha_inicio, $fecha_fin);
    }

    public function eliminarGestion($id)
    {
        return $this->dGestion->eliminarGestion($id);
    }

    public function obtenerGestiones()
    {
        return $this->dGestion->obtenerGestiones();
    }
}
