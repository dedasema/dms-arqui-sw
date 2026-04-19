<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

$nCarrera = new NCarrera();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $carreras = $nCarrera->obtenerCarreras();
            echo json_encode(['status' => true, 'data' => $carreras]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nombre = $input['nombre'] ?? '';
            $sigla = $input['sigla'] ?? '';

            if ($id > 0) {
                $nCarrera->editarCarrera($id, $nombre, $sigla);
                echo json_encode(['status' => true, 'message' => 'Carrera actualizada correctamente.']);
            } else {
                $result = $nCarrera->crearCarrera($nombre, $sigla);
                echo json_encode(['status' => true, 'message' => 'Carrera creada correctamente.', 'id' => $result['id']]);
            }
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nCarrera->eliminarCarrera($id);
            echo json_encode(['status' => true, 'message' => 'Carrera eliminada correctamente.']);
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
