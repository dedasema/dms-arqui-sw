<?php

class NComentario
{
    private $dComentario;

    public function __construct()
    {
        $this->dComentario = new DComentario();
    }

    public function insertar($mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id, $nuevoEstado = null)
    {
        $result = $this->dComentario->insertar($mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id);

        if ($nuevoEstado) {
            $nProyecto = new NProyecto();
            $nProyecto->actualizarEstado($proyecto_id, $nuevoEstado);
        }

        // TODO: procesarNotificaciones (pendiente implementar NNotificacion)
        // $this->procesarNotificaciones($proyecto_id);

        return $result;
    }

    public function obtenerComentarios($version_id)
    {
        return $this->dComentario->obtenerComentarios($version_id);
    }

    public function obtenerPorId($id)
    {
        return $this->dComentario->obtenerPorId($id);
    }

    private function procesarNotificaciones($proyecto_id)
    {
        // Instanciar NAsignacion para obtener estudiantes vinculados
        // Por cada estudiante, instanciar NNotificacion y crear alerta
    }
}
