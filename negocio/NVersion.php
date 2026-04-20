<?php

class NVersion
{
    private $dVersion;

    public function __construct()
    {
        $this->dVersion = new DVersion();
    }

    public function subir($nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id)
    {
        $nProyecto = new NProyecto();
        $estado = $nProyecto->obtenerEstado($proyecto_id);

        // Auto-incrementar el número de versión
        $ultimoNumero = $this->dVersion->obtenerUltimoNumero($proyecto_id);
        $numero  = $ultimoNumero + 1;

        $result = $this->dVersion->subir(
            $nombre, $peso_bytes, $proyecto_id,
            $ruta_archivo, $usuario_id, $estado, $numero
        );

        // $this->procesarNotificaciones($proyecto_id, $nuevoNumero, $usuario_id);

        return $result;
    }

    public function obtenerVersiones($proyecto_id)
    {
        return $this->dVersion->obtenerVersiones($proyecto_id);
    }

    public function obtenerPorId($id)
    {
        return $this->dVersion->obtenerPorId($id);
    }

    // private function procesarNotificaciones($proyecto_id, $numero_version, $autor_id)
    // {
    //     $nProyecto = new NProyecto();
    //     $asignaciones = $nProyecto->obtenerAsignaciones($proyecto_id);
    //     
    //     $nNotificacion = new NNotificacion();
    //     $mensaje = "Se ha subido la Versión $numero_version para el Proyecto #$proyecto_id. Por favor, realiza la revisión.";
    // 
    //     foreach ($asignaciones as $asig) {
    //         // Notificamos a roles que no sean Estudiante
    //         if ($asig['rol'] !== 'Estudiante' && $asig['usuario_id'] != $autor_id) {
    //             $nNotificacion->insertar($asig['usuario_id'], $mensaje);
    //         }
    //     }
    // }
}
