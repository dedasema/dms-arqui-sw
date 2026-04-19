<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';

$nProyecto = new NProyecto();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            // Todos los roles autenticados pueden ver los proyectos
            checkAccess(['Administrador', 'Docente', 'Estudiante']);
            $proyectos = $nProyecto->obtenerProyectos();
            echo json_encode(['status' => true, 'data' => $proyectos]);
            break;

        case 'POST':
            // Solo Administrador y Estudiante pueden crear/editar
            checkAccess(['Administrador', 'Estudiante']);
            $input = json_decode(file_get_contents('php://input'), true);
            $id           = $input['id'] ?? 0;
            $titulo       = $input['titulo'] ?? '';
            $estado       = $input['estado'] ?? 'Iniciado';
            $carrera_id   = $input['carrera_id'] ?? 0;
            $modalidad_id = $input['modalidad_id'] ?? 0;
            $gestion_id   = $input['gestion_id'] ?? 0;

            if ($id > 0) {
                $nProyecto->editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
                echo json_encode(['status' => true, 'message' => 'Proyecto actualizado correctamente.']);
            } else {
                $result = $nProyecto->crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
                echo json_encode(['status' => true, 'message' => 'Proyecto creado correctamente.', 'id' => $result['id']]);
            }
            break;

        case 'DELETE':
            checkAccess(['Administrador']);
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nProyecto->eliminarProyecto($id);
            echo json_encode(['status' => true, 'message' => 'Proyecto eliminado correctamente.']);
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
