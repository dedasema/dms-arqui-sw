<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

$nUsuario = new NUsuario();
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $usuarios = $nUsuario->obtenerUsuarios();
            echo json_encode(['status' => true, 'data' => $usuarios]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            $id          = $input['id'] ?? 0;
            $nombre      = $input['nombre_completo'] ?? '';
            $correo      = $input['correo'] ?? '';
            $contrasena  = $input['contrasena'] ?? '';
            $rol         = $input['rol'] ?? '';
            $codigo      = $input['codigo'] ?? '';
            $carrera_id  = $input['carrera_id'] ?? 0;

            if ($id > 0) {
                // Si la contraseña viene vacía, conservamos el hash actual
                if (empty($contrasena)) {
                    $contrasena = $nUsuario->obtenerContrasenaActual($id);
                } else {
                    $contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
                }
                $nUsuario->editarUsuario($id, $nombre, $correo, $contrasena, $rol, $codigo, $carrera_id);
                echo json_encode(['status' => true, 'message' => 'Usuario actualizado correctamente.']);
            } else {
                // Siempre encriptar al crear
                $contrasena = password_hash($contrasena, PASSWORD_BCRYPT);
                $result = $nUsuario->crearUsuario($nombre, $correo, $contrasena, $rol, $codigo, $carrera_id);
                echo json_encode(['status' => true, 'message' => 'Usuario creado correctamente.', 'id' => $result['id']]);
            }
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? 0;
            $nUsuario->eliminarUsuario($id);
            echo json_encode(['status' => true, 'message' => 'Usuario eliminado correctamente.']);
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
