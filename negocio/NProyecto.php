<?php

class NProyecto
{
    private $dProyecto;

    public function __construct()
    {
        $this->dProyecto = new DProyecto();
    }

    public function crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)
    {
        return $this->dProyecto->crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)
    {
        return $this->dProyecto->editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function eliminarProyecto($id)
    {
        return $this->dProyecto->eliminarProyecto($id);
    }

    public function obtenerProyectos()
    {
        return $this->dProyecto->obtenerProyectos();
    }

    public function actualizarEstado($id, $estado)
    {
        return $this->dProyecto->actualizarEstado($id, $estado);
    }
}
