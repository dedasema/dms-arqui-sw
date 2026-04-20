<?php
class PCarrera {
    private $nCarrera;

    public function __construct() {
        $this->nCarrera = new NCarrera();
    }

    public function obtenerCarreras() {
        return $this->nCarrera->obtenerCarreras();
    }

    public function crearCarrera($input) {
        $this->nCarrera->crearCarrera($input['nombre'], $input['sigla']);
    }

    public function editarCarrera($input) {
        $this->nCarrera->editarCarrera($input['id'], $input['nombre'], $input['sigla']);
    }

    public function eliminarCarrera($id) {
        $this->nCarrera->eliminarCarrera($id);
    }
}
