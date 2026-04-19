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

        // $this->procesarNotificaciones($proyecto_id, $version_id, $usuario_id);

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

    // private function procesarNotificaciones($proyecto_id, $version_id, $autor_id)
    // {
    //     // Notificar a los estudiantes sobre el nuevo comentario/revisión
    //     $nProyecto = new NProyecto();
    //     $asignaciones = $nProyecto->obtenerAsignaciones($proyecto_id);
    //     
    //     $nNotificacion = new NNotificacion();
    //     $mensaje_texto = "Tienes un nuevo comentario en la Versión $version_id de tu Proyecto #$proyecto_id.";
    // 
    //     foreach ($asignaciones as $asig) {
    //         if ($asig['rol'] === 'Estudiante' && $asig['usuario_id'] != $autor_id) {
    //             $nNotificacion->insertar($asig['usuario_id'], $mensaje_texto);
    //         }
    //     }
    // }
}
