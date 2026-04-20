<?php
class PModalidad {
    private $nModalidad;

    public function __construct() {
        $this->nModalidad = new NModalidad();
    }

    public function obtenerModalidades() {
        return $this->nModalidad->obtenerModalidades();
    }

    public function crearModalidad($input) {
        $this->nModalidad->crearModalidad($input['nombre'], $input['descripcion']);
    }

    public function editarModalidad($input) {
        $this->nModalidad->editarModalidad($input['id'], $input['nombre'], $input['descripcion']);
    }

    public function eliminarModalidad($id) {
        $this->nModalidad->eliminarModalidad($id);
    }
}
