<?php
require_once __DIR__ . '/ModalidadDecorator.php';

class ModalidadCacheDecorator extends ModalidadDecorator
{
    public function obtenerModalidades()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['cache_modalidades'])) {
            // ==========================================
            // DEBUG PARA LA DEFENSA: Muestra que usa RAM
            // ==========================================
            echo "<script>console.log('⚡ [DECORADOR CACHÉ]: Datos cargados desde la memoria. 0 consultas a PostgreSQL.');</script>";
            
            return $_SESSION['cache_modalidades'];
        }

        // Si no hay caché, delega la ejecución para ir a la BD
        $datos = parent::obtenerModalidades();

        // ==========================================
        // DEBUG PARA LA DEFENSA: Muestra que usa BD
        // ==========================================
        echo "<script>console.log('🗄️ [DECORADOR CACHÉ]: Caché vacía. Consultando a PostgreSQL y guardando en memoria...');</script>";
        
        // Guarda en memoria temporal
        $_SESSION['cache_modalidades'] = $datos;

        return $datos;
    }

    public function crearModalidad($nombre, $descripcion)
    {
        $this->limpiarCache();
        return parent::crearModalidad($nombre, $descripcion);
    }

    public function editarModalidad($id, $nombre, $descripcion)
    {
        $this->limpiarCache();
        return parent::editarModalidad($id, $nombre, $descripcion);
    }

    public function eliminarModalidad($id)
    {
        $this->limpiarCache();
        return parent::eliminarModalidad($id);
    }

    private function limpiarCache()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['cache_modalidades'])) {
            unset($_SESSION['cache_modalidades']);
            // ==========================================
            // DEBUG PARA LA DEFENSA: Muestra invalidación
            // ==========================================
            echo "<script>console.log('🗑️ [DECORADOR CACHÉ]: Datos alterados. Caché limpiada automáticamente.');</script>";
        }
    }
}