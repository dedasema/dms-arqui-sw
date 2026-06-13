<?php

interface ICarrera
{
    public function crearCarrera($nombre, $sigla);
    public function editarCarrera($id, $nombre, $sigla);
    public function eliminarCarrera($id);
    public function obtenerCarreras();
}