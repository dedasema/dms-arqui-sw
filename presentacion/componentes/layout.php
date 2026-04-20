<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$rol = $_SESSION['rol'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DMS - FICCT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
    
    <aside class="w-64 bg-gray-900 text-white min-h-screen p-4 flex flex-col">
        <div class="mb-8 pl-2">
            <h1 class="text-2xl font-bold text-blue-400">DMS - FICCT</h1>
            <p class="text-gray-400 text-sm mt-1">Sistema de Gestión Documental</p>
            <p class="text-gray-500 text-xs mt-2 uppercase"><?php echo htmlspecialchars($rol ?? 'INVITADO'); ?></p>
        </div>

        <nav class="flex-1 space-y-1">
            <a href="/presentacion/dashboard/" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="home" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Dashboard</span>
            </a>

            <?php if (in_array($rol, ['Estudiante', 'Docente'])): ?>
            <div class="pt-6 pb-2 px-3">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Gestión Documental</p>
            </div>
            <a href="/presentacion/proyectos/" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="folder" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Proyectos</span>
            </a>
            <?php endif; ?>
            <?php if (in_array($rol, ['Administrador'])): ?>
            <div class="pt-6 pb-2 px-3">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Administración</p>
            </div>
            <a href="/presentacion/proyectos/" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="folder" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Proyectos</span>
            </a>
            <?php endif; ?>

            <?php if ($rol === 'Administrador'): ?>
            <a href="/presentacion/usuarios/" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="users" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Usuarios</span>
            </a>
            <a href="/presentacion/asignaciones/" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="user-check" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Asignaciones</span>
            </a>
 
            <a href="/presentacion/carreras/" class="flex items-center space-x-3 py-2 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="book-open" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Carreras</span>
            </a>
            <a href="/presentacion/modalidades/" class="flex items-center space-x-3 py-2 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="layers" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Modalidades</span>
            </a>
            <a href="/presentacion/gestiones/" class="flex items-center space-x-3 py-2 px-3 rounded-lg hover:bg-gray-800 transition">
                <i data-lucide="calendar" class="w-5 h-5 text-gray-400"></i>
                <span class="font-medium text-sm">Gestiones</span>
            </a>
            <?php endif; ?>
        </nav>

        <div class="mt-auto pt-4 border-t border-gray-800">
            <a href="/presentacion/auth/logout.php" class="flex items-center space-x-3 py-2.5 px-3 rounded-lg hover:bg-red-900/40 text-red-400 transition group">
                <i data-lucide="log-out" class="w-5 h-5 group-hover:transform group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-medium text-sm">Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen">
        <header class="bg-white border-b border-gray-200 relative z-10 px-6 py-4 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800 tracking-tight" id="page-title">Sistema</h2>
            <div class="flex items-center space-x-4">
                <div class="flex flex-col items-end">
                    <span class="text-sm font-semibold text-gray-700 leading-tight"><?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?></span>
                    <span class="text-xs text-gray-500 font-medium"><?php echo htmlspecialchars($rol ?? 'Invitado'); ?></span>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-blue-400 shadow-inner flex items-center justify-center text-white font-bold text-lg select-none ring-2 ring-gray-100">
                    <?php echo substr($_SESSION['nombre_completo'] ?? 'U', 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="p-6 overflow-y-auto bg-gray-50 flex-1">
            <!-- END LAYOUT START -->
