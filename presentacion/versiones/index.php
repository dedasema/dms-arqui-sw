<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

require_once 'PVersion.php';
require_once '../proyectos/PProyecto.php';

$pVersion = new PVersion();
$pProyecto = new PProyecto();

$proyecto_id = $_GET['proyecto_id'] ?? 0;

// Procesar Subida de Archivo (Sincrónico)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'subir') {
    checkAccess(['Estudiante']);
    try {
        $pVersion->subirVersion($_POST, $_FILES);
        header("Location: /presentacion/versiones/?proyecto_id=" . $_POST['proyecto_id']);
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Procesar Descarga
if (isset($_GET['action']) && $_GET['action'] === 'descargar') {
    $id = $_GET['id'] ?? 0;
    $v = $pVersion->obtenerVersionPorId($id);
    if ($v) {
        $rutaAbsoluta = __DIR__ . '/../../' . $v['ruta_archivo'];
        if (file_exists($rutaAbsoluta)) {
            $ext = strtolower(pathinfo($v['nombre'], PATHINFO_EXTENSION));
            $mimeMap = ['pdf' => 'application/pdf', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'doc' => 'application/msword', 'txt' => 'text/plain'];
            $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . basename($v['nombre']) . '"');
            header('Content-Length: ' . filesize($rutaAbsoluta));
            readfile($rutaAbsoluta);
            exit;
        }
    }
}

$proyectos = $pProyecto->obtenerProyectos();
$versiones = $proyecto_id ? $pVersion->obtenerVersiones($proyecto_id) : [];

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Versiones Documentales';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Historial de Versiones</h3>
    <p class="text-sm text-gray-400 mt-0.5">Sube y consulta los avances documentales de cada proyecto.</p>
</div>

<?php if (isset($error)): ?>
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 text-sm border border-red-100">
    <?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Panel izquierdo: Selector y Subida -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                <i data-lucide="filter" class="w-4 h-4 text-gray-400"></i>
                <span>Seleccionar Proyecto</span>
            </h4>
            <form method="GET" action="">
                <select name="proyecto_id" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">-- Seleccionar proyecto --</option>
                    <?php foreach($proyectos as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= $proyecto_id == $p['id'] ? 'selected' : '' ?>>
                            [<?= $p['estado'] ?>] <?= htmlspecialchars($p['titulo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <?php if ($_SESSION['rol'] === 'Estudiante' && $proyecto_id): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h4 class="text-sm font-semibold text-gray-700 mb-4 flex items-center space-x-2">
                <i data-lucide="upload" class="w-4 h-4 text-blue-500"></i>
                <span>Subir Avance</span>
            </h4>
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="subir">
                <input type="hidden" name="proyecto_id" value="<?= $proyecto_id ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Documento</label>
                    <input type="file" name="archivo" accept=".pdf,.docx,.doc" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition flex items-center justify-center space-x-2">
                    <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                    <span>Subir de Avance</span>
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Panel derecho: Historial -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden lg:col-span-2">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center space-x-2">
            <i data-lucide="history" class="w-4 h-4 text-gray-400"></i>
            <h4 class="text-sm font-semibold text-gray-700">Historial de versiones</h4>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nº</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Archivo</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peso</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subido por</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$proyecto_id): ?>
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Selecciona un proyecto para ver su historial.</td></tr>
                <?php elseif (empty($versiones)): ?>
                    <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400 text-sm">Sin versiones registradas para este proyecto.</td></tr>
                <?php else: ?>
                    <?php foreach ($versiones as $v): ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 text-sm font-mono text-gray-600">v<?= $v['numero'] ?></td>
                        <td class="px-4 py-2.5 text-sm text-gray-800 font-medium truncate max-w-[200px]"><?= htmlspecialchars($v['nombre']) ?></td>
                        <td class="px-4 py-2.5 text-sm text-gray-500"><?= round($v['peso_bytes']/1024, 1) ?> KB</td>
                        <td class="px-4 py-2.5 text-sm text-gray-500"><?= htmlspecialchars($v['nombre_usuario']) ?></td>
                        <td class="px-4 py-2.5 text-sm text-right space-x-3">
                            <a href="/presentacion/comentarios/?proyecto_id=<?= $proyecto_id ?>&version_id=<?= $v['id'] ?>" class="text-teal-600 hover:text-teal-800 transition inline-flex items-center space-x-1">
                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Revisión</span>
                            </a>
                            <a href="?action=descargar&id=<?= $v['id'] ?>&proyecto_id=<?= $proyecto_id ?>" class="text-blue-600 hover:text-blue-800 transition inline-flex items-center space-x-1">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                <span class="hidden sm:inline">Descargar</span>
                            </a>
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
