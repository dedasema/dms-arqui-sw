<?php
require_once __DIR__ . '/ModalidadDecorator.php';

class ModalidadSanitizadorDecorator extends ModalidadDecorator
{
    public function crearModalidad($nombre, $descripcion)
    {
        $nombreLimpio = trim(strtoupper($nombre));
        $descLimpia = trim(strip_tags($descripcion));

        return parent::crearModalidad($nombreLimpio, $descLimpia);
    }

    public function editarModalidad($id, $nombre, $descripcion)
    {
        $nombreLimpio = trim(strtoupper($nombre));
        $descLimpia = trim(strip_tags($descripcion));

        return parent::editarModalidad($id, $nombreLimpio, $descLimpia);
    }
}