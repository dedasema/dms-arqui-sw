<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - PROYECTOS
 */
class PProyecto {
    private $nProyecto;
    private $nCarrera;
    private $nModalidad;
    private $nGestion;

    public function __construct() {
        $this->nProyecto = new NProyecto();
        $this->nCarrera = new NCarrera();
        $this->nModalidad = new NModalidad();
        $this->nGestion = new NGestion();
    }

    public function obtenerProyectos() {
        return $this->nProyecto->obtenerProyectos();
    }

    public function obtenerCarreras() {
        return $this->nCarrera->obtenerCarreras();
    }

    public function obtenerModalidades() {
        return $this->nModalidad->obtenerModalidades();
    }

    public function obtenerGestiones() {
        return $this->nGestion->obtenerGestiones();
    }

    public function crearProyecto($input) {
        $titulo       = $input['titulo'] ?? '';
        $estado       = $input['estado'] ?? 'Iniciado';
        $carrera_id   = !empty($input['carrera_id']) ? $input['carrera_id'] : null;
        $modalidad_id = !empty($input['modalidad_id']) ? $input['modalidad_id'] : null;
        $gestion_id   = !empty($input['gestion_id']) ? $input['gestion_id'] : null;

        return $this->nProyecto->crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function editarProyecto($input) {
        $id           = $input['id'];
        $titulo       = $input['titulo'] ?? '';
        $estado       = $input['estado'] ?? 'Iniciado';
        $carrera_id   = !empty($input['carrera_id']) ? $input['carrera_id'] : null;
        $modalidad_id = !empty($input['modalidad_id']) ? $input['modalidad_id'] : null;
        $gestion_id   = !empty($input['gestion_id']) ? $input['gestion_id'] : null;

        return $this->nProyecto->editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id);
    }

    public function eliminarProyecto($id) {
        return $this->nProyecto->eliminarProyecto($id);
    }
}

$pProyecto = new PProyecto();

// Procesar formulario web sincrónico directamente con la Capa P
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'crear') {
        checkAccess(['Administrador', 'Estudiante']);
        $pProyecto->crearProyecto($_POST);
    } elseif ($action === 'editar') {
        checkAccess(['Administrador']);
        $pProyecto->editarProyecto($_POST);
    } elseif ($action === 'eliminar') {
        checkAccess(['Administrador']);
        $pProyecto->eliminarProyecto($_POST['id']);
    }
    header("Location: /presentacion/proyectos/");
    exit;
}

// Obtener datos para la vista
$proyectos = $pProyecto->obtenerProyectos();
$carreras = $pProyecto->obtenerCarreras();
$modalidades = $pProyecto->obtenerModalidades();
$gestiones = $pProyecto->obtenerGestiones();

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>
    window.USER_ROLE = "<?php echo $_SESSION['rol'] ?? ''; ?>";
    document.getElementById('page-title').textContent = 'Gestionar Proyectos';
</script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Proyectos de Titulación</h3>
        <p class="text-sm text-gray-400 mt-0.5">Gestiona los proyectos de grado registrados en el sistema.</p>
    </div>
    <?php if ($_SESSION['rol'] === 'Administrador'): ?>
    <button onclick="abrirModalNuevo()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Nuevo Proyecto</span>
    </button>
    <?php endif; ?>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Título</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Modalidad</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($proyectos)): ?>
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">No hay proyectos registrados.</td></tr>
            <?php else: ?>
                <?php foreach ($proyectos as $p): ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono"><?= $p['id'] ?></td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium"><?= htmlspecialchars($p['titulo']) ?></td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700"><?= $p['estado'] ?></span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($p['nombre_carrera'] ?? '—') ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($p['nombre_modalidad']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($p['codigo_gestion']) ?></td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <a href="/presentacion/versiones/?proyecto_id=<?= $p['id'] ?>" class="text-green-600 hover:text-green-800 transition" title="Versiones del Proyecto">
                            <i data-lucide="layers" class="w-4 h-4 inline"></i>
                        </a>

                        <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                        <button onclick="editar(<?= $p['id'] ?>, '<?= addslashes($p['titulo']) ?>', '<?= $p['estado'] ?>', <?= $p['carrera_id'] ?: 0 ?>, <?= $p['modalidad_id'] ?>, <?= $p['gestion_id'] ?>)" class="text-blue-600 hover:text-blue-800 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <?php endif; ?>

                        <?php if ($_SESSION['rol'] === 'Administrador'): ?>
                        <form method="POST" action="" class="inline" onsubmit="return confirm('¿Eliminar este proyecto?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal-proyecto" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nuevo Proyecto</h3>
        </div>
        <form id="form-proyecto" method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" name="action" id="form-action" value="crear">
            <input type="hidden" name="id" id="input-id" value="0">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título del Proyecto</label>
                <input type="text" name="titulo" id="input-titulo" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm" placeholder="Ej: Sistema de Gestión de Inventarios">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado" id="select-estado" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="Iniciado">Iniciado</option>
                        <option value="Asignado">Asignado</option>
                        <option value="En Revisión">En Revisión</option>
                        <option value="Observado">Observado</option>
                        <option value="Aprobado">Aprobado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                    <select name="carrera_id" id="select-carrera" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">Seleccionar carrera...</option>
                        <?php foreach($carreras as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                    <select name="modalidad_id" id="select-modalidad" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">Seleccionar modalidad...</option>
                        <?php foreach($modalidades as $m): ?>
                        <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['nombre']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gestión</label>
                    <select name="gestion_id" id="select-gestion" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">Seleccionar gestión...</option>
                        <?php foreach($gestiones as $g): ?>
                        <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['codigo']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" onclick="cerrarModal()" class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">Cancelar</button>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">Guardar</button>
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
    document.getElementById('form-proyecto').reset();
    document.getElementById('modal-titulo').textContent = 'Nuevo Proyecto';
    document.getElementById('modal-proyecto').classList.remove('hidden');
}

function editar(id, titulo, estado, carrera_id, modalidad_id, gestion_id) {
    document.getElementById('form-action').value = 'editar';
    document.getElementById('input-id').value = id;
    document.getElementById('input-titulo').value = titulo;
    document.getElementById('select-estado').value = estado;
    document.getElementById('select-carrera').value = carrera_id || '';
    document.getElementById('select-modalidad').value = modalidad_id;
    document.getElementById('select-gestion').value = gestion_id;
    
    document.getElementById('modal-titulo').textContent = 'Editar Proyecto';
    document.getElementById('modal-proyecto').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-proyecto').classList.add('hidden');
}

lucide.createIcons();
</script>
</html>
