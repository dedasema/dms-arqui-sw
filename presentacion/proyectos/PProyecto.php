<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - PROYECTOS
 */
class PProyecto {
    private $nProyecto;

    public function __construct() {
        $this->nProyecto = new NProyecto();
    }

    public function obtenerProyectos() {
        return $this->nProyecto->obtenerProyectos();
    }

    public function crearProyecto($input) {
        $titulo       = $input['titulo'] ?? '';
        $estado       = $input['estado'] ?? 'Iniciado';
        $carrera_id   = !empty($input['carrera_id']) ? $input['carrera_id'] : null;
        $modalidad_id = !empty($input['modalidad_id']) ? $input['modalidad_id'] : null;
        $gestion_id   = !empty($input['gestion_id']) ? $input['gestion_id'] : null;

        return $this->nProyecto->crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function editarProyecto($input) {
        $id           = $input['id'];
        $titulo       = $input['titulo'] ?? '';
        $estado       = $input['estado'] ?? 'Iniciado';
        $carrera_id   = !empty($input['carrera_id']) ? $input['carrera_id'] : null;
        $modalidad_id = !empty($input['modalidad_id']) ? $input['modalidad_id'] : null;
        $gestion_id   = !empty($input['gestion_id']) ? $input['gestion_id'] : null;

        return $this->nProyecto->editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function eliminarProyecto($id) {
        return $this->nProyecto->eliminarProyecto($id);
    }
}
