<?php
require_once __DIR__ . '/IModalidad.php';

abstract class ModalidadDecorator implements IModalidad
{
    protected $componente;

    public function __construct(IModalidad $componente)
    {
        $this->componente = $componente;
    }

    public function crearModalidad($nombre, $descripcion)
    {
        return $this->componente->crearModalidad($nombre, $descripcion);
    }

    public function editarModalidad($id, $nombre, $descripcion)
    {
        return $this->componente->editarModalidad($id, $nombre, $descripcion);
    }

    public function eliminarModalidad($id)
    {
        return $this->componente->eliminarModalidad($id);
    }

    public function obtenerModalidades()
    {
        return $this->componente->obtenerModalidades();
    }
}