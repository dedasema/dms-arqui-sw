<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

$nGestion = new NGestion();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $gestiones = $nGestion->obtenerGestiones();
            echo json_encode(['status' => true, 'data' => $gestiones]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $codigo = $input['codigo'] ?? '';
            $fecha_inicio = $input['fecha_inicio'] ?? '';
            $fecha_fin = $input['fecha_fin'] ?? '';

            if ($id > 0) {
                $nGestion->editarGestion($id, $codigo, $fecha_inicio, $fecha_fin);
                echo json_encode(['status' => true, 'message' => 'Gestión actualizada correctamente.']);
            } else {
                $result = $nGestion->crearGestion($codigo, $fecha_inicio, $fecha_fin);
                echo json_encode(['status' => true, 'message' => 'Gestión creada correctamente.', 'id' => $result['id']]);
            }
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nGestion->eliminarGestion($id);
            echo json_encode(['status' => true, 'message' => 'Gestión eliminada correctamente.']);
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
