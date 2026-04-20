<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

require_once 'PGestion.php';
$pGestion = new PGestion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'crear') {
        $pGestion->crearGestion($_POST);
    } elseif ($action === 'editar') {
        $pGestion->editarGestion($_POST);
    } elseif ($action === 'eliminar') {
        $pGestion->eliminarGestion($_POST['id']);
    }
    header("Location: /presentacion/gestiones/");
    exit;
}

$gestiones = $pGestion->obtenerGestiones();

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestiones Académicas';</script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Gestiones</h3>
        <p class="text-sm text-gray-400 mt-0.5">Administra los periodos académicos del sistema.</p>
    </div>
    <button onclick="abrirModalNuevo()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="calendar-plus" class="w-4 h-4"></i>
        <span>Nueva Gestión</span>
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Inicio</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Fin</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($gestiones)): ?>
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No hay gestiones registradas.</td></tr>
            <?php else: ?>
                <?php foreach ($gestiones as $g): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono"><?= $g['id'] ?></td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium"><?= htmlspecialchars($g['codigo']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($g['fecha_inicio']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($g['fecha_fin']) ?></td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(<?= $g['id'] ?>, '<?= addslashes($g['codigo']) ?>', '<?= addslashes($g['fecha_inicio']) ?>', '<?= addslashes($g['fecha_fin']) ?>')" class="text-blue-600 hover:text-blue-800 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <form method="POST" action="" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta gestión?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id" value="<?= $g['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal-gestion" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nueva Gestión</h3>
        </div>
        <form method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" name="action" id="form-action" value="crear">
            <input type="hidden" name="id" id="input-id" value="0">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                <input type="text" name="codigo" id="input-codigo" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Ej: 2024-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                <input type="date" name="fecha_inicio" id="input-inicio" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                <input type="date" name="fecha_fin" id="input-fin" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm">Guardar</button>
            </div>
        </form>
    </div>
</div>

</div>
</main>
</body>
<script>
function abrirModalNuevo() {
    document.getElementById('form-action').value = 'crear';
    document.getElementById('input-id').value = '0';
    document.getElementById('input-codigo').value = '';
    document.getElementById('input-inicio').value = '';
    document.getElementById('input-fin').value = '';
    document.getElementById('modal-titulo').textContent = 'Nueva Gestión';
    document.getElementById('modal-gestion').classList.remove('hidden');
}

function editar(id, codigo, inicio, fin) {
    document.getElementById('form-action').value = 'editar';
    document.getElementById('input-id').value = id;
    document.getElementById('input-codigo').value = codigo;
    document.getElementById('input-inicio').value = inicio;
    document.getElementById('input-fin').value = fin;
    document.getElementById('modal-titulo').textContent = 'Editar Gestión';
    document.getElementById('modal-gestion').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-gestion').classList.add('hidden');
}

lucide.createIcons();
</script>
</html>
