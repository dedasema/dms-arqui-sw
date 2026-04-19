<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

try {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $version_id = $_GET['version_id'] ?? 0;
        $nComentario = new NComentario();
        $comentarios = $nComentario->obtenerComentarios($version_id);
        echo json_encode(['status' => true, 'data' => $comentarios]);
        exit;
    }

    if ($method === 'POST') {
        $version_id   = $_POST['version_id'] ?? 0;
        $proyecto_id  = $_POST['proyecto_id'] ?? 0;
        $mensaje      = trim($_POST['mensaje'] ?? '');
        $nuevo_estado = $_POST['nuevo_estado'] ?? null;
        $usuario_id   = $_SESSION['usuario_id'];

        if (!$mensaje) {
            http_response_code(400);
            echo json_encode(['status' => false, 'error' => 'El mensaje es obligatorio.']);
            exit;
        }

        $rutaRelativa = null;

        if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo     = $_FILES['archivo'];
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

        $nComentario = new NComentario();
        $result = $nComentario->insertar($mensaje, $rutaRelativa, $version_id, $usuario_id, $proyecto_id, $nuevo_estado);

        echo json_encode(['status' => true, 'message' => 'Comentario registrado correctamente.', 'id' => $result['id']]);
        exit;
    }

    http_response_code(405);
    echo json_encode(['status' => false, 'error' => 'Método no permitido.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'error' => 'Error interno del servidor.']);
}
