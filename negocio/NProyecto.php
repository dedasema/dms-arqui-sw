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

    public function obtenerEstado($id)
    {
        return $this->dProyecto->obtenerEstado($id);
    }
    public function actualizarEstado($id, $estado)
    {
        return $this->dProyecto->actualizarEstado($id, $estado);
    }

    // --- Métodos de Asignaciones (Fase 9 / CU5) ---

    public function crearAsignacion($proyecto_id, $usuarios)
    {
        //Eliminar si hubiera alguna asignación anterior
        $this->eliminarAsignacion($proyecto_id);

        $dAsignacion = new DDetalleAsignacion();
        foreach ($usuarios as $u) {
            $dAsignacion->crearAsignacion($u['usuario_id'], $u['rol'], $proyecto_id);
        }
        // Cambiar estado del proyecto a "Asignado"
        $this->actualizarEstado($proyecto_id, 'Asignado');
    }

    public function eliminarAsignacion($proyecto_id)
    {
        $dAsignacion = new DDetalleAsignacion();
        return $dAsignacion->eliminarAsignacion($proyecto_id);
    }

    public function obtenerAsignaciones($proyecto_id)
    {
        $dAsignacion = new DDetalleAsignacion();
        return $dAsignacion->obtenerAsignaciones($proyecto_id);
    }

    public function obtenerPorId($id)
    {
        return $this->dProyecto->obtenerPorId($id);
    }
}
