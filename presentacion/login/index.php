<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';

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

$pAuth = new PAuth();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $pass = trim($_POST['contrasena'] ?? '');
    
    try {
        if ($pAuth->login($correo, $pass)) {
            header("Location: /presentacion/dashboard/");
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TesisFlow — Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e3a5f 100%); }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .input-field { background: rgba(255, 255, 255, 0.07); border: 1px solid rgba(255, 255, 255, 0.15); color: #fff; transition: border-color 0.2s, background 0.2s; }
        .input-field::placeholder { color: rgba(255,255,255,0.35); }
        .input-field:focus { outline: none; border-color: #60a5fa; background: rgba(255, 255, 255, 0.12); }
        .btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); transition: opacity 0.2s, transform 0.1s; }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }
        .error-box { background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-600/30 mb-4">
                <i data-lucide="graduation-cap" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">DMS-FICCT</h1>
            <p class="text-blue-300 text-sm mt-1">Sistema de Gestión Documental</p>
        </div>

        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <h2 class="text-xl font-semibold text-white mb-1">Bienvenido</h2>
            <p class="text-gray-400 text-sm mb-6">Ingresa tus credenciales para continuar.</p>

            <?php if ($error): ?>
            <div class="error-box rounded-xl p-3 mb-4 flex items-center space-x-2 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        <input type="email" name="correo" required class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm" placeholder="correo@universidad.edu">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        <input type="password" name="contrasena" id="input-contrasena" required class="input-field w-full rounded-xl pl-10 pr-10 py-3 text-sm" placeholder="••••••••">
                        <button type="button" onclick="togglePass()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                            <i id="eye-icon" data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full rounded-xl py-3 text-white text-sm font-semibold shadow-md flex items-center justify-center space-x-2 mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Iniciar Sesión Sincrónica</span>
                </button>
            </form>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">TesisFlow &copy; <?= date('Y') ?></p>
    </div>

<script>
function togglePass() {
    const input = document.getElementById('input-contrasena');
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }
    lucide.createIcons();
}
lucide.createIcons();
</script>
</body>
</html>
