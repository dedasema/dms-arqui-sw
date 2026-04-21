<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador']);

/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - USUARIOS
 */
class PUsuario {
    private $nUsuario;
    private $nCarrera;

    public function __construct() {
        $this->nUsuario = new NUsuario();
        $this->nCarrera = new NCarrera();
    }

    public function obtenerUsuarios() {
        return $this->nUsuario->obtenerUsuarios();
    }

    public function obtenerCarreras() {
        return $this->nCarrera->obtenerCarreras();
    }

    public function crearUsuario($input) {
        $contrasena = password_hash($input['contrasena'], PASSWORD_BCRYPT);
        return $this->nUsuario->crearUsuario(
            $input['nombre_completo'], 
            $input['correo'], 
            $contrasena, 
            $input['rol'], 
            $input['codigo'] ?? null, 
            !empty($input['carrera_id']) ? $input['carrera_id'] : null
        );
    }

    public function editarUsuario($input) {
        $id = $input['id'];
        if (empty($input['contrasena'])) {
            $contrasena = $this->nUsuario->obtenerContrasenaActual($id);
        } else {
            $contrasena = password_hash($input['contrasena'], PASSWORD_BCRYPT);
        }
        
        return $this->nUsuario->editarUsuario(
            $id, 
            $input['nombre_completo'], 
            $input['correo'], 
            $contrasena, 
            $input['rol'], 
            $input['codigo'] ?? null, 
            !empty($input['carrera_id']) ? $input['carrera_id'] : null
        );
    }

    public function eliminarUsuario($id) {
        return $this->nUsuario->eliminarUsuario($id);
    }
}

$pUsuario = new PUsuario();

// Procesar formulario web sincrónico directamente con la Capa P
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'crear') {
        $pUsuario->crearUsuario($_POST);
    } elseif ($action === 'editar') {
        $pUsuario->editarUsuario($_POST);
    } elseif ($action === 'eliminar') {
        $pUsuario->eliminarUsuario($_POST['id']);
    }
    // Patrón Post/Redirect/Get para evitar reenvío de formulario
    header("Location: /presentacion/usuarios/");
    exit;
}

// Obtener datos para la vista
$usuarios = $pUsuario->obtenerUsuarios();
$carreras = $pUsuario->obtenerCarreras();

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Usuarios';</script>

<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Usuarios</h3>
        <p class="text-sm text-gray-400 mt-0.5">Administra los estudiantes, docentes y administradores del sistema.</p>
    </div>
    <button onclick="abrirModalNuevo()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="user-plus" class="w-4 h-4"></i>
        <span>Nuevo Usuario</span>
    </button>
</div>

<!-- Tabla Sincrónica PHP -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usuarios)): ?>
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">No hay usuarios registrados.</td></tr>
            <?php else: ?>
                <?php foreach ($usuarios as $u): ?>
                <?php 
                    $rolBadge = [
                        'Administrador' => 'bg-purple-100 text-purple-700',
                        'Docente' => 'bg-blue-100 text-blue-700',
                        'Estudiante' => 'bg-green-100 text-green-700'
                    ][$u['rol']] ?? 'bg-gray-100 text-gray-600';
                ?>
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono"><?= $u['id'] ?></td>
                    <td class="px-6 py-3 text-sm text-gray-600"><?= htmlspecialchars($u['codigo'] ?? '—') ?></td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium"><?= htmlspecialchars($u['nombre_completo']) ?></td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($u['correo']) ?></td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $rolBadge ?>"><?= $u['rol'] ?></span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500"><?= htmlspecialchars($u['nombre_carrera'] ?? '—') ?></td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(<?= $u['id'] ?>, '<?= addslashes($u['nombre_completo']) ?>', '<?= addslashes($u['correo']) ?>', '<?= $u['rol'] ?>', '<?= addslashes($u['codigo'] ?? '') ?>', <?= $u['carrera_id'] ?: 0 ?>)" class="text-blue-600 hover:text-blue-800 transition">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <form method="POST" class="inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                            <input type="hidden" name="action" value="eliminar">
                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
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
<div id="modal-usuario" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nuevo Usuario</h3>
        </div>
        <form id="form-usuario" method="POST" action="" class="p-6 space-y-4">
            <input type="hidden" name="action" id="form-action" value="crear">
            <input type="hidden" name="id" id="input-id" value="0">
            
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre Completo</label>
                    <input type="text" name="nombre_completo" id="input-nombre" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
                    <input type="email" name="correo" id="input-correo" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                    <input type="text" name="codigo" id="input-codigo" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label id="label-contrasena" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="contrasena" id="input-contrasena" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
                    <select name="rol" id="select-rol" onchange="toggleCarrera(this.value)" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">Seleccionar rol...</option>
                        <option value="Estudiante">Estudiante</option>
                        <option value="Docente">Docente</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <div id="container-carrera" class="col-span-2 hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Carrera (Solo estudiantes)</label>
                    <select name="carrera_id" id="select-carrera" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm">
                        <option value="">Ninguna</option>
                        <?php foreach($carreras as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?> (<?= htmlspecialchars($c['sigla']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
function toggleCarrera(rol) {
    const container = document.getElementById('container-carrera');
    if (rol === 'Estudiante') {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        document.getElementById('select-carrera').value = '';
    }
}

function abrirModalNuevo() {
    document.getElementById('form-action').value = 'crear';
    document.getElementById('input-id').value = '0';
    document.getElementById('form-usuario').reset();
    toggleCarrera('');
    document.getElementById('label-contrasena').textContent = 'Contraseña';
    document.getElementById('modal-titulo').textContent = 'Nuevo Usuario';
    document.getElementById('input-contrasena').required = true;
    document.getElementById('modal-usuario').classList.remove('hidden');
}

function editar(id, nombre_completo, correo, rol, codigo, carrera_id) {
    document.getElementById('form-action').value = 'editar';
    document.getElementById('input-id').value = id;
    document.getElementById('input-nombre').value = nombre_completo;
    document.getElementById('input-correo').value = correo;
    document.getElementById('input-contrasena').value = '';
    document.getElementById('input-contrasena').required = false;
    document.getElementById('select-rol').value = rol;
    document.getElementById('select-carrera').value = carrera_id || '';
    
    toggleCarrera(rol);

    document.getElementById('label-contrasena').textContent = 'Nueva Contraseña (vacío para no cambiar)';
    document.getElementById('modal-titulo').textContent = 'Editar Usuario';
    document.getElementById('modal-usuario').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-usuario').classList.add('hidden');
}

lucide.createIcons();
</script>
</html>
