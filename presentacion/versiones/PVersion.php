<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - VERSIONES
 */
class PVersion {
    private $nVersion;

    public function __construct() {
        $this->nVersion = new NVersion();
    }

    public function obtenerVersiones($proyecto_id) {
        return $this->nVersion->obtenerVersiones($proyecto_id);
    }

    public function obtenerVersionPorId($id) {
        return $this->nVersion->obtenerPorId($id);
    }

    public function subirVersion($input, $files) {
        $proyecto_id = $input['proyecto_id'] ?? 0;
        $usuario_id  = $_SESSION['usuario_id'];

        if (!isset($files['archivo']) || $files['archivo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("No se recibió ningún archivo válido.");
        }

        $archivo     = $files['archivo'];
        $nombre      = $archivo['name'];
        $peso_bytes  = $archivo['size'];
        $tmp_path    = $archivo['tmp_name'];
        $ext         = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        // Calcular el siguiente número
        $dVersion = new DVersion();
        $numero   = $dVersion->obtenerUltimoNumero($proyecto_id) + 1;

        $nombreArchivo = "{$proyecto_id}_{$numero}_" . time() . ".{$ext}";
        $dirDestino    = __DIR__ . '/../../uploads/versiones/';

        if (!is_dir($dirDestino)) {
            mkdir($dirDestino, 0755, true);
        }

        $rutaDestino = $dirDestino . $nombreArchivo;
        if (!move_uploaded_file($tmp_path, $rutaDestino)) {
            throw new Exception("Error al mover el archivo al servidor.");
        }

        $rutaRelativa = 'uploads/versiones/' . $nombreArchivo;
        return $this->nVersion->subir($nombre, $peso_bytes, $proyecto_id, $rutaRelativa, $usuario_id);
    }

    public function descargar($id) {
        $v = $this->obtenerVersionPorId($id);
        if ($v) {
            $rutaAbsoluta = __DIR__ . '/../../' . $v['ruta_archivo'];
            if (file_exists($rutaAbsoluta)) {
                $ext = strtolower(pathinfo($v['nombre'], PATHINFO_EXTENSION));
                $mimeMap = [
                    'pdf' => 'application/pdf', 
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'doc' => 'application/msword', 
                    'txt' => 'text/plain'
                ];
                $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="' . basename($v['nombre']) . '"');
                header('Content-Length: ' . filesize($rutaAbsoluta));
                readfile($rutaAbsoluta);
                exit;
            }
        }
        throw new Exception("Archivo no encontrado.");
    }
}
