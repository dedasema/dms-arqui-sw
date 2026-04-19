<?php

class NVersion
{
    private $dVersion;

    public function __construct()
    {
        $this->dVersion = new DVersion();
    }

    public function subir($nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id, $estado = 'Pendiente')
    {
        // Auto-incrementar el número de versión
        $ultimoNumero = $this->dVersion->obtenerUltimoNumero($proyecto_id);
        $nuevoNumero  = $ultimoNumero + 1;

        $result = $this->dVersion->subir(
            $nombre, $peso_bytes, $proyecto_id,
            $ruta_archivo, $usuario_id, $estado, $nuevoNumero
        );

        // TODO: procesarNotificaciones (pendiente implementar NNotificacion y tabla notificaciones)
        // $this->procesarNotificaciones($proyecto_id);

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

    // Stub — se conectará a NNotificacion cuando esa fase se implemente
    private function procesarNotificaciones($proyecto_id)
    {
        // Instanciar NAsignacion para obtener docentes asignados
        // Por cada docente, instanciar NNotificacion y crear alerta
    }
}
