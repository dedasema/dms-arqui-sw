<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - ASIGNACIONES
 */
class PAsignacion {
    private $nProyecto;

    public function __construct() {
        $this->nProyecto = new NProyecto();
    }

    public function obtenerAsignaciones($proyecto_id) {
        return $this->nProyecto->obtenerAsignaciones($proyecto_id);
    }

    public function guardarAsignaciones($proyecto_id, $usuarios_ids) {
        $nUsuario = new NUsuario();
        $todos = $nUsuario->obtenerUsuarios();
        $roles_map = [];
        foreach($todos as $t) {
            $roles_map[$t['id']] = $t['rol'];
        }

        $usuarios_preparados = [];
        foreach ($usuarios_ids as $uid) {
            $usuarios_preparados[] = [
                'usuario_id' => $uid,
                'rol' => $roles_map[$uid] ?? 'Docente'
            ];
        }
        return $this->nProyecto->crearAsignacion($proyecto_id, $usuarios_preparados);
    }

    public function eliminarAsignaciones($proyecto_id) {
        return $this->nProyecto->eliminarAsignacion($proyecto_id);
    }
}
