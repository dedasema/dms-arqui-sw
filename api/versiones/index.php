<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

try {
    $proyecto_id = $_GET['proyecto_id'] ?? 0;
    $nVersion = new NVersion();
    $versiones = $nVersion->obtenerVersiones($proyecto_id);
    echo json_encode(['status' => true, 'data' => $versiones]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'error' => 'Error interno del servidor.']);
}
