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

    public function descargar($id) {
        $c = $this->obtenerComentarioPorId($id);
        if ($c && $c['ruta_archivo']) {
            $rutaAbsoluta = __DIR__ . '/../../' . $c['ruta_archivo'];
            if (file_exists($rutaAbsoluta)) {
                $ext = strtolower(pathinfo($c['ruta_archivo'], PATHINFO_EXTENSION));
                $mimeMap = [
                    'pdf' => 'application/pdf', 
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'doc' => 'application/msword', 
                    'txt' => 'text/plain'
                ];
                $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="Adjunto_Revision_' . $id . '.' . $ext . '"');
                header('Content-Length: ' . filesize($rutaAbsoluta));
                readfile($rutaAbsoluta);
                exit;
            }
        }
        throw new Exception("Adjunto no encontrado.");
    }
}
