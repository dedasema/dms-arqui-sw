<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - AUTENTICACIÓN
 */
class PAuth {
    private $nUsuario;

    public function __construct() {
        $this->nUsuario = new NUsuario();
    }

    public function login($correo, $pass) {
        $usuario = $this->nUsuario->obtenerPorCorreo($correo);

        if (!$usuario || !password_verify($pass, $usuario['contrasena'])) {
            throw new Exception("Credenciales incorrectas.");
        }

        // Arrancar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['usuario_id']      = $usuario['id'];
        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
        $_SESSION['rol']              = $usuario['rol'];
        $_SESSION['correo']           = $usuario['correo'];

        return true;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }
}
