<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

$nProyecto = new NProyecto();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $proyecto_id = $_GET['proyecto_id'] ?? 0;
            $asignaciones = $nProyecto->obtenerAsignaciones($proyecto_id);
            echo json_encode(['status' => true, 'data' => $asignaciones]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $proyecto_id = $input['proyecto_id'] ?? 0;
            $usuarios    = $input['usuarios'] ?? [];

            // Primero eliminar asignaciones anteriores del proyecto (reemplazar)
            $nProyecto->eliminarAsignacion($proyecto_id);
            // Luego insertar las nuevas
            $nProyecto->crearAsignacion($proyecto_id, $usuarios);
            echo json_encode(['status' => true, 'message' => 'Asignaciones guardadas correctamente.']);
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $proyecto_id = $input['proyecto_id'] ?? 0;
            $nProyecto->eliminarAsignacion($proyecto_id);
            echo json_encode(['status' => true, 'message' => 'Asignaciones eliminadas correctamente.']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['status' => false, 'error' => 'Método no permitido.']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'error' => 'Error interno del servidor.']);
}
