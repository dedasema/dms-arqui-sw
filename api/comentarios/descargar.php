<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

$id = $_GET['id'] ?? 0;

try {
    $nComentario = new NComentario();
    $comentario  = $nComentario->obtenerPorId($id);

    if (!$comentario || !$comentario['ruta_archivo']) {
        http_response_code(404);
        echo 'Archivo adjunto no encontrado.';
        exit;
    }

    $rutaAbsoluta = __DIR__ . '/../../' . $comentario['ruta_archivo'];

    if (!file_exists($rutaAbsoluta)) {
        http_response_code(404);
        echo 'Archivo no encontrado en el servidor.';
        exit;
    }

    $ext      = strtolower(pathinfo($rutaAbsoluta, PATHINFO_EXTENSION));
    $mimeMap  = [
        'pdf'  => 'application/pdf',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'doc'  => 'application/msword',
        'txt'  => 'text/plain',
    ];
    $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';

    header('Content-Type: ' . $mimeType);
    header('Content-Disposition: attachment; filename="' . basename($comentario['ruta_archivo']) . '"');
    header('Content-Length: ' . filesize($rutaAbsoluta));
    header('Cache-Control: no-cache, must-revalidate');

    readfile($rutaAbsoluta);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo 'Error al procesar la descarga.';
}
