<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Estudiante', 'Administrador']);

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['status' => false, 'error' => 'Método no permitido.']);
        exit;
    }

    $proyecto_id = $_POST['proyecto_id'] ?? 0;
    $usuario_id  = $_SESSION['usuario_id'];

    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['status' => false, 'error' => 'No se recibió ningún archivo válido.']);
        exit;
    }

    $archivo     = $_FILES['archivo'];
    $nombre      = $archivo['name'];
    $peso_bytes  = $archivo['size'];
    $tmp_path    = $archivo['tmp_name'];
    $ext         = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

    // Calcular el siguiente número para la nomenclatura del archivo
    $dVersion = new DVersion();
    $numero   = $dVersion->obtenerUltimoNumero($proyecto_id) + 1;

    $nombreArchivo = "{$proyecto_id}_{$numero}_" . time() . ".{$ext}";
    $dirDestino    = __DIR__ . '/../../uploads/versiones/';

    if (!is_dir($dirDestino)) {
        mkdir($dirDestino, 0755, true);
    }

    $rutaDestino = $dirDestino . $nombreArchivo;
    if (!move_uploaded_file($tmp_path, $rutaDestino)) {
        http_response_code(500);
        echo json_encode(['status' => false, 'error' => 'Error al mover el archivo al servidor.']);
        exit;
    }

    // Ruta relativa para guardar en BD (usada por el endpoint de descarga)
    $rutaRelativa = 'uploads/versiones/' . $nombreArchivo;

    $nVersion = new NVersion();
    $result   = $nVersion->subir($nombre, $peso_bytes, $proyecto_id, $rutaRelativa, $usuario_id);

    echo json_encode(['status' => true, 'message' => 'Versión subida correctamente.', 'id' => $result['id']]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'error' => 'Error interno del servidor.']);
}
