<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';

require_once __DIR__ . '/../../negocio/ProxyCarrera.php';

/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - CARRERAS
 */
class PCarrera {
    private ICarrera $nCarrera;

    public function __construct() {
        $this->nCarrera = new ProxyCarrera();
    }

    public function obtenerCarreras() {
        return $this->nCarrera->obtenerCarreras();
    }

    public function crearCarrera($input) {
        $this->nCarrera->crearCarrera($input['nombre'], $input['sigla']);
    }

    public function editarCarrera($input) {
        $this->nCarrera->editarCarrera($input['id'], $input['nombre'], $input['sigla']);
    }

    public function eliminarCarrera($id) {
        $this->nCarrera->eliminarCarrera($id);
    }
}

$pCarrera = new PCarrera();
$errorMensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';
        if ($action === 'crear') {
            $pCarrera->crearCarrera($_POST);
        } elseif ($action === 'editar') {
            $pCarrera->editarCarrera($_POST);
        } elseif ($action === 'eliminar') {
            $pCarrera->eliminarCarrera($_POST['id']);
        }
        header("Location: /presentacion/carreras/");
        exit;
    } catch (Exception $e) {
        if ($e->getMessage() === 'UNAUTHENTICATED') {
            header('Location: /presentacion/login/');
            exit;
        } elseif ($e->getMessage() === 'UNAUTHORIZED') {
            header('Location: /presentacion/dashboard/?error=forbidden');
            exit;
        } else {
            $errorMensaje = $e->getMessage();
        }
    }
}

try {
    $carreras = $pCarrera->obtenerCarreras();
} catch (Exception $e) {
    if ($e->getMessage() === 'UNAUTHENTICATED') {
        header('Location: /presentacion/login/');
        exit;
    }
    $carreras = [];
    $errorMensaje = "Error de autorización: " . $e->getMessage();
}

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Carreras';</script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Carreras</h3>
        <p class="text-sm text-gray-400 mt-0.5">Administra las carreras disponibles en el sistema.</p>
    </div>
    <button onclick="abrirModalNuevo()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Nueva Carrera</span>
    </button>
</div>

<?php if (!empty($errorMensaje)): ?>
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Aviso:</strong>
    <span class="block sm:inline"><?= htmlspecialchars($errorMensaje) ?></span>
</div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Sigla</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($carreras)): ?>
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No hay carreras registradas.</td></tr>
            <?php else: ?>
                <?php foreach ($carreras as $c): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono"><?= $c['id'] ?></td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium"><?= htmlspecialchars($c['nombre']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($c['sigla']) ?></td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(<?= $c['id'] ?>, '<?= addslashes($c['nombre']) ?>', '<?= addslashes($c['sigla']) ?>')" class="text-blue-600 hover:text-blue-800 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <form method="POST" action="" class="inline" onsubmit="return confirm('¿Está seguro de eliminar esta carrera?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
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

<div id="modal-carrera" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nueva Carrera</h3>
        </div>
        <form method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" name="action" id="form-action" value="crear">
            <input type="hidden" name="id" id="input-id" value="0">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Carrera</label>
                <input type="text" name="nombre" id="input-nombre" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Ej: Ingeniería de Sistemas">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sigla</label>
                <input type="text" name="sigla" id="input-sigla" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Ej: SIS">
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
    document.getElementById('input-sigla').value = '';
    document.getElementById('modal-titulo').textContent = 'Nueva Carrera';
    document.getElementById('modal-carrera').classList.remove('hidden');
}

function editar(id, nombre, sigla) {
    document.getElementById('form-action').value = 'editar';
    document.getElementById('input-id').value = id;
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('input-sigla').value = sigla;
    document.getElementById('modal-titulo').textContent = 'Editar Carrera';
    document.getElementById('modal-carrera').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-carrera').classList.add('hidden');
}

lucide.createIcons();
</script>
</html>