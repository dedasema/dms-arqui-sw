<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - MODALIDADES
 */
class PModalidad {
    private $nModalidad;

    public function __construct() {
        $this->nModalidad = new NModalidad();
    }

    public function obtenerModalidades() {
        return $this->nModalidad->obtenerModalidades();
    }

    public function crearModalidad($input) {
        $this->nModalidad->crearModalidad($input['nombre'], $input['descripcion']);
    }

    public function editarModalidad($input) {
        $this->nModalidad->editarModalidad($input['id'], $input['nombre'], $input['descripcion']);
    }

    public function eliminarModalidad($id) {
        $this->nModalidad->eliminarModalidad($id);
    }
}

$pModalidad = new PModalidad();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'crear') {
        $pModalidad->crearModalidad($_POST);
    } elseif ($action === 'editar') {
        $pModalidad->editarModalidad($_POST);
    } elseif ($action === 'eliminar') {
        $pModalidad->eliminarModalidad($_POST['id']);
    }
    header("Location: /presentacion/modalidades/");
    exit;
}

$modalidades = $pModalidad->obtenerModalidades();

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Modalidades Titulación';</script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Modalidades</h3>
        <p class="text-sm text-gray-400 mt-0.5">Administra las formas de titulación permitidas.</p>
    </div>
    <button onclick="abrirModalNuevo()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Nueva Modalidad</span>
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Descripción</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($modalidades)): ?>
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No hay modalidades registradas.</td></tr>
            <?php else: ?>
                <?php foreach ($modalidades as $m): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono"><?= $m['id'] ?></td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium"><?= htmlspecialchars($m['nombre']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500 max-w-xs truncate"><?= htmlspecialchars($m['descripcion']) ?></td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(<?= $m['id'] ?>, '<?= addslashes($m['nombre']) ?>', '<?= addslashes($m['descripcion']) ?>')" class="text-blue-600 hover:text-blue-800 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <form method="POST" action="" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta modalidad?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
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
<div id="modal-modalidad" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nueva Modalidad</h3>
        </div>
        <form method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" name="action" id="form-action" value="crear">
            <input type="hidden" name="id" id="input-id" value="0">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" id="input-nombre" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Ej: Proyecto de Grado">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" id="input-descripcion" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Breve descripción..."></textarea>
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
    document.getElementById('input-nombre').value = '';
    document.getElementById('input-descripcion').value = '';
    document.getElementById('modal-titulo').textContent = 'Nueva Modalidad';
    document.getElementById('modal-modalidad').classList.remove('hidden');
}

function editar(id, nombre, descripcion) {
    document.getElementById('form-action').value = 'editar';
    document.getElementById('input-id').value = id;
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('input-descripcion').value = descripcion;
    document.getElementById('modal-titulo').textContent = 'Editar Modalidad';
    document.getElementById('modal-modalidad').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-modalidad').classList.add('hidden');
}

lucide.createIcons();
</script>
</html>
