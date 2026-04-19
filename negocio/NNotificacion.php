<?php

class NNotificacion
{
    private $dNotificacion;

    public function __construct()
    {
        $this->dNotificacion = new DNotificacion();
    }

    public function insertar($usuario_id, $mensaje)
    {
        return $this->dNotificacion->insertar($usuario_id, $mensaje);
    }
}
