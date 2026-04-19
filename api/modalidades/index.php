<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

$nModalidad = new NModalidad();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $modalidades = $nModalidad->obtenerModalidades();
            echo json_encode(['status' => true, 'data' => $modalidades]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nombre = $input['nombre'] ?? '';
            $descripcion = $input['descripcion'] ?? '';

            if ($id > 0) {
                $nModalidad->editarModalidad($id, $nombre, $descripcion);
                echo json_encode(['status' => true, 'message' => 'Modalidad actualizada correctamente.']);
            } else {
                $result = $nModalidad->crearModalidad($nombre, $descripcion);
                echo json_encode(['status' => true, 'message' => 'Modalidad creada correctamente.', 'id' => $result['id']]);
            }
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nModalidad->eliminarModalidad($id);
            echo json_encode(['status' => true, 'message' => 'Modalidad eliminada correctamente.']);
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
