<?php
class PGestion {
    private $nGestion;

    public function __construct() {
        $this->nGestion = new NGestion();
    }

    public function obtenerGestiones() {
        return $this->nGestion->obtenerGestiones();
    }

    public function crearGestion($input) {
        $this->nGestion->crearGestion($input['codigo'], $input['fecha_inicio'], $input['fecha_fin']);
    }

    public function editarGestion($input) {
        $this->nGestion->editarGestion($input['id'], $input['codigo'], $input['fecha_inicio'], $input['fecha_fin']);
    }

    public function eliminarGestion($id) {
        $this->nGestion->eliminarGestion($id);
    }
}
