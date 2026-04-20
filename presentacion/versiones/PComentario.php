<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - COMENTARIOS
 */
class PComentario {
    private $nComentario;

    public function __construct() {
        $this->nComentario = new NComentario();
    }

    public function obtenerComentarios($version_id) {
        return $this->nComentario->obtenerComentarios($version_id);
    }

    public function obtenerComentarioPorId($id) {
        return $this->nComentario->obtenerPorId($id);
    }

    public function guardarComentario($input, $files) {
        $version_id   = $input['version_id'] ?? 0;
        $proyecto_id  = $input['proyecto_id'] ?? 0;
        $mensaje      = trim($input['mensaje'] ?? '');
        $estado       = $input['estado'] ?? null;
        $usuario_id   = $_SESSION['usuario_id'];

        if (!$mensaje) {
            throw new Exception("El mensaje es obligatorio.");
        }

        $rutaRelativa = null;

        if (isset($files['archivo']) && $files['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo     = $files['archivo'];
            $nombre      = $archivo['name'];
            $tmp_path    = $archivo['tmp_name'];
            $ext         = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

            $nombreArchivo = "{$proyecto_id}_rev_{$version_id}_" . time() . ".{$ext}";
            $dirDestino    = __DIR__ . '/../../uploads/comentarios/';

            if (!is_dir($dirDestino)) {
                mkdir($dirDestino, 0755, true);
            }

            $rutaDestino = $dirDestino . $nombreArchivo;
            if (move_uploaded_file($tmp_path, $rutaDestino)) {
                $rutaRelativa = 'uploads/comentarios/' . $nombreArchivo;
            }
        }

        return $this->nComentario->insertar($mensaje, $rutaRelativa, $version_id, $usuario_id, $proyecto_id, $estado);
    }
}
