<?php

interface IModalidad
{
    public function crearModalidad($nombre, $descripcion);
    public function editarModalidad($id, $nombre, $descripcion);
    public function eliminarModalidad($id);
    public function obtenerModalidades();
}