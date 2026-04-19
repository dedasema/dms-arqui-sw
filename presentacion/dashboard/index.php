<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);
?>

<script>document.getElementById('page-title').textContent = 'Dashboard';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">
        Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? 'Usuario'); ?> 👋
    </h3>
    <p class="text-sm text-gray-400 mt-0.5">
        Rol: <span class="font-medium text-blue-600"><?php echo htmlspecialchars($_SESSION['rol'] ?? ''); ?></span>
        &mdash; Selecciona un módulo desde el menú lateral para comenzar.
    </p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

    <!-- Proyectos -->
    <a href="/presentacion/proyectos/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-blue-200 transition">
        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-100 transition">
            <i data-lucide="folder" class="w-6 h-6 text-blue-600"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Proyectos</p>
            <p class="text-xs text-gray-400 mt-0.5">Gestiona tus proyectos de titulación.</p>
        </div>
    </a>

    <!-- Versiones -->
    <a href="/presentacion/versiones/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-indigo-200 transition">
        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-100 transition">
            <i data-lucide="file-up" class="w-6 h-6 text-indigo-600"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Versiones Documentales</p>
            <p class="text-xs text-gray-400 mt-0.5">Sube y revisa documentos.</p>
        </div>
    </a>

    <!-- Comentarios -->
    <a href="/presentacion/comentarios/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-purple-200 transition">
        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-100 transition">
            <i data-lucide="message-square" class="w-6 h-6 text-purple-600"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Revisiones</p>
            <p class="text-xs text-gray-400 mt-0.5">Revisa y anota observaciones.</p>
        </div>
    </a>

    <?php if ($_SESSION['rol'] === 'Administrador'): ?>
    <!-- Usuarios -->
    <a href="/presentacion/usuarios/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-green-200 transition">
        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition">
            <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Usuarios</p>
            <p class="text-xs text-gray-400 mt-0.5">Administra cuentas del sistema.</p>
        </div>
    </a>

    <!-- Asignaciones -->
    <a href="/presentacion/asignaciones/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-yellow-200 transition">
        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-yellow-100 transition">
            <i data-lucide="user-check" class="w-6 h-6 text-yellow-600"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Asignaciones</p>
            <p class="text-xs text-gray-400 mt-0.5">Asigna tutores y tribunales.</p>
        </div>
    </a>

    <!-- Configuración -->
    <a href="/presentacion/carreras/" class="group bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center space-x-4 hover:shadow-md hover:border-gray-300 transition">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-gray-200 transition">
            <i data-lucide="settings" class="w-6 h-6 text-gray-500"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Configuración</p>
            <p class="text-xs text-gray-400 mt-0.5">Carreras, Modalidades y Gestiones.</p>
        </div>
    </a>
    <?php endif; ?>

</div>

        </div><!-- Cierre del contenedor del layout -->
    </main>
</body>
<script>lucide.createIcons();</script>
</html>
