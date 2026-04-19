<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TesisFlow — Iniciar Sesión</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 50%, #1e3a5f 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .input-field {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #fff;
            transition: border-color 0.2s, background 0.2s;
        }
        .input-field::placeholder { color: rgba(255,255,255,0.35); }
        .input-field:focus {
            outline: none;
            border-color: #60a5fa;
            background: rgba(255, 255, 255, 0.12);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transition: opacity 0.2s, transform 0.1s;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); }
        .error-box {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">

    <!-- Decoración de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        <!-- Logo / Marca -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg shadow-blue-600/30 mb-4">
                <i data-lucide="graduation-cap" class="w-8 h-8 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">DMS-FICCT</h1>
            <p class="text-blue-300 text-sm mt-1">Sistema de Gestión Documental</p>
        </div>

        <!-- Tarjeta del formulario -->
        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <h2 class="text-xl font-semibold text-white mb-1">Bienvenido de vuelta</h2>
            <p class="text-gray-400 text-sm mb-6">Ingresa tus credenciales para continuar.</p>

            <!-- Mensaje de error -->
            <div id="error-login" class="hidden error-box rounded-xl p-3 mb-4 flex items-center space-x-2 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 flex-shrink-0"></i>
                <span id="error-texto">Credenciales incorrectas.</span>
            </div>

            <form id="form-login" class="space-y-5" novalidate>
                <div>
                    <label for="input-correo" class="block text-sm font-medium text-gray-300 mb-1.5">Correo electrónico</label>
                    <div class="relative">
                        <i data-lucide="mail" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        <input
                            type="email"
                            id="input-correo"
                            autocomplete="email"
                            placeholder="correo@universidad.edu"
                            required
                            class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm">
                    </div>
                </div>

                <div>
                    <label for="input-contrasena" class="block text-sm font-medium text-gray-300 mb-1.5">Contraseña</label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        <input
                            type="password"
                            id="input-contrasena"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            class="input-field w-full rounded-xl pl-10 pr-10 py-3 text-sm">
                        <button type="button" id="toggle-pass" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition">
                            <i data-lucide="eye" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="btn-login"
                        class="btn-primary w-full rounded-xl py-3 text-white text-sm font-semibold shadow-md flex items-center justify-center space-x-2 mt-2">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    <span>Iniciar Sesión</span>
                </button>
            </form>
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            TesisFlow &copy; <?php echo date('Y'); ?> — Todos los derechos reservados.
        </p>
    </div>

<script>
lucide.createIcons();

const toggleBtn = document.getElementById('toggle-pass');
const passInput = document.getElementById('input-contrasena');
toggleBtn.addEventListener('click', () => {
    const isPass = passInput.type === 'password';
    passInput.type = isPass ? 'text' : 'password';
    toggleBtn.innerHTML = isPass
        ? '<i data-lucide="eye-off" class="w-4 h-4"></i>'
        : '<i data-lucide="eye" class="w-4 h-4"></i>';
    lucide.createIcons();
});

document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    const correo    = document.getElementById('input-correo').value.trim();
    const contrasena = document.getElementById('input-contrasena').value;
    const btn       = document.getElementById('btn-login');
    const errBox    = document.getElementById('error-login');
    const errTexto  = document.getElementById('error-texto');

    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i><span>Verificando...</span>';
    lucide.createIcons();
    errBox.classList.add('hidden');

    try {
        const res = await fetch('/api/auth/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ correo, contrasena })
        });

        const data = await res.json();

        if (data.status) {
            window.location.href = data.redirect;
        } else {
            errTexto.textContent = data.error || 'Credenciales incorrectas.';
            errBox.classList.remove('hidden');
            lucide.createIcons();
        }
    } catch {
        errTexto.textContent = 'Error de conexión. Comprueba tu red.';
        errBox.classList.remove('hidden');
        lucide.createIcons();
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="log-in" class="w-4 h-4"></i><span>Iniciar Sesión</span>';
        lucide.createIcons();
    }
});
</script>
</body>
</html>
