<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

require_once 'PAsignacion.php';
require_once '../proyectos/PProyecto.php';
require_once '../usuarios/PUsuario.php';

$pAsignacion = new PAsignacion();
$pProyecto = new PProyecto();
$pUsuario = new PUsuario();

$proyecto_id = $_GET['proyecto_id'] ?? 0;

// Procesar Guardado de Asignaciones (Sincrónico)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid = $_POST['proyecto_id'] ?? 0;
    $usuarios_asignados = $_POST['usuarios'] ?? []; // Array de IDs de usuarios
    
    if ($pid) {
        $pAsignacion->guardarAsignaciones($pid, $usuarios_asignados);
        header("Location: /presentacion/asignaciones/?proyecto_id=$pid");
        exit;
    }
}

// Obtener datos para la vista
$proyectos = $pProyecto->obtenerProyectos();
$usuarios_disponibles = $pUsuario->obtenerUsuarios(); // Filtrar por Docente/etc si es necesario en N
$asignaciones_actuales = $proyecto_id ? $pAsignacion->obtenerAsignaciones($proyecto_id) : [];

// Crear un array de IDs asignados para marcar los checkboxes
$ids_asignados = array_column($asignaciones_actuales, 'usuario_id');

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Asignaciones';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Asignación de Tribunal y Tutores</h3>
    <p class="text-sm text-gray-400 mt-0.5">Asigna docentes y tutores a cada proyecto de titulación.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Panel izquierdo: Formulario de asignación -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col space-y-5">
        <form method="GET" action="" id="form-filtro">
            <label class="block text-sm font-medium text-gray-700 mb-1">Seleccionar Proyecto</label>
            <select name="proyecto_id" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="">-- Seleccionar proyecto --</option>
                <?php foreach($proyectos as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $proyecto_id == $p['id'] ? 'selected' : '' ?>>
                    [<?= $p['estado'] ?>] <?= htmlspecialchars($p['titulo']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($proyecto_id): ?>
        <form method="POST" action="" class="space-y-4">
            <input type="hidden" name="proyecto_id" value="<?= $proyecto_id ?>">
            
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-3">Usuarios Disponibles</p>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-2 border border-gray-100 rounded-lg p-3 bg-gray-50">
                    <?php foreach($usuarios_disponibles as $u): ?>
                        <?php if (in_array($u['rol'], ['Docente', 'Estudiante'])): ?>
                        <label class="flex items-center space-x-3 p-2 rounded-md hover:bg-white transition cursor-pointer border border-transparent hover:border-gray-200">
                            <input type="checkbox" name="usuarios[]" value="<?= $u['id'] ?>" 
                                   <?= in_array($u['id'], $ids_asignados) ? 'checked' : '' ?>
                                   class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($u['nombre_completo']) ?></span>
                                <span class="text-[10px] text-gray-400 uppercase"><?= $u['rol'] ?></span>
                            </div>
                        </label>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-3 rounded-xl shadow-lg shadow-blue-100 transition flex items-center justify-center space-x-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span>Guardar Cambios</span>
            </button>
        </form>
        <?php else: ?>
        <div class="flex-1 flex flex-col items-center justify-center text-center p-8 border-2 border-dashed border-gray-100 rounded-2xl">
            <i data-lucide="info" class="w-8 h-8 text-gray-300 mb-2"></i>
            <p class="text-gray-400 text-sm">Selecciona un proyecto para gestionar sus asignaciones.</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Panel derecho: Lista actual -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50">
            <h4 class="text-sm font-semibold text-gray-700">Asignaciones actuales</h4>
            <p class="text-xs text-gray-400 mt-0.5">Usuarios que ya están vinculados a este proyecto.</p>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Rol</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$proyecto_id): ?>
                    <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 text-sm">Sin proyecto seleccionado.</td></tr>
                <?php elseif (empty($asignaciones_actuales)): ?>
                    <tr><td colspan="2" class="px-6 py-10 text-center text-gray-400 text-sm">No hay docentes asignados todavía.</td></tr>
                <?php else: ?>
                    <?php foreach($asignaciones_actuales as $a): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xs ring-1 ring-blue-100">
                                    <?= substr($a['nombre_completo'] ?? 'U', 0, 1) ?>
                                </div>
                                <span class="text-sm font-medium text-gray-800"><?= htmlspecialchars($a['nombre_completo'] ?? 'Sin nombre') ?></span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 uppercase tracking-tight"><?= $a['rol'] ?? 'Docente' ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</div>
</main>
</body>
<script>lucide.createIcons();</script>
</html>
