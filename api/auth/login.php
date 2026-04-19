<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => false, 'error' => 'Método no permitido.']);
    exit;
}

$data    = json_decode(file_get_contents('php://input'), true);
$correo  = trim($data['correo'] ?? '');
$pass    = trim($data['contrasena'] ?? '');

if (!$correo || !$pass) {
    http_response_code(400);
    echo json_encode(['status' => false, 'error' => 'Correo y contraseña son obligatorios.']);
    exit;
}

try {
    $nUsuario = new NUsuario();
    $usuario  = $nUsuario->obtenerPorCorreo($correo);

    if (!$usuario || !password_verify($pass, $usuario['contrasena'])) {
        http_response_code(401);
        echo json_encode(['status' => false, 'error' => 'Credenciales incorrectas.']);
        exit;
    }

    // Arrancar sesión con los datos del usuario autenticado
    $_SESSION['usuario_id']     = $usuario['id'];
    $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
    $_SESSION['rol']             = $usuario['rol'];
    $_SESSION['correo']          = $usuario['correo'];

    echo json_encode(['status' => true, 'redirect' => '/presentacion/dashboard/']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => false, 'error' => 'Error interno del servidor.']);
}
