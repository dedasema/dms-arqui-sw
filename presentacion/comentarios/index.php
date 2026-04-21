<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once __DIR__ . '/../../config/session.php';
checkAccess(['Administrador', 'Docente', 'Estudiante']);

/**
 * CLASE DE PRESENTACIÓN (BOUNDARY) - COMENTARIOS
 */
class PComentario {
    private $nComentario;
    private $nVersion;
    private $nProyecto;

    public function __construct() {
        $this->nComentario = new NComentario();
        $this->nVersion = new NVersion();
        $this->nProyecto = new NProyecto();
    }

    public function obtenerComentarios($version_id) {
        return $this->nComentario->obtenerComentarios($version_id);
    }

    public function obtenerComentarioPorId($id) {
        return $this->nComentario->obtenerPorId($id);
    }

    public function obtenerVersionPorId($version_id) {
        return $this->nVersion->obtenerPorId($version_id);
    }

    public function guardarComentario($input, $files) {
        $version_id   = $input['version_id'] ?? 0;
        $proyecto_id  = $input['proyecto_id'] ?? 0;
        $mensaje      = trim($input['mensaje'] ?? '');
        $estado       = $input['estado'] ?? null;
        $usuario_id   = $_SESSION['usuario_id'];

        if (!$mensaje) {
            throw new Exception("El mensaje es obligatorio.");
        }

        $rutaRelativa = null;

        if (isset($files['archivo']) && $files['archivo']['error'] === UPLOAD_ERR_OK) {
            $archivo     = $files['archivo'];
            $nombre      = $archivo['name'];
            $tmp_path    = $archivo['tmp_name'];
            $ext         = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

            $nombreArchivo = "{$proyecto_id}_rev_{$version_id}_" . time() . ".{$ext}";
            $dirDestino    = __DIR__ . '/../../uploads/comentarios/';

            if (!is_dir($dirDestino)) {
                mkdir($dirDestino, 0755, true);
            }

            $rutaDestino = $dirDestino . $nombreArchivo;
            if (move_uploaded_file($tmp_path, $rutaDestino)) {
                $rutaRelativa = 'uploads/comentarios/' . $nombreArchivo;
            }
        }

        return $this->nComentario->insertar($mensaje, $rutaRelativa, $version_id, $usuario_id, $proyecto_id, $estado);
    }

    public function descargar($id) {
        $c = $this->obtenerComentarioPorId($id);
        if ($c && $c['ruta_archivo']) {
            $rutaAbsoluta = __DIR__ . '/../../' . $c['ruta_archivo'];
            if (file_exists($rutaAbsoluta)) {
                $ext = strtolower(pathinfo($c['ruta_archivo'], PATHINFO_EXTENSION));
                $mimeMap = [
                    'pdf' => 'application/pdf', 
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'doc' => 'application/msword', 
                    'txt' => 'text/plain'
                ];
                $mimeType = $mimeMap[$ext] ?? 'application/octet-stream';
                header('Content-Type: ' . $mimeType);
                header('Content-Disposition: attachment; filename="Adjunto_Revision_' . $id . '.' . $ext . '"');
                header('Content-Length: ' . filesize($rutaAbsoluta));
                readfile($rutaAbsoluta);
                exit;
            }
        }
        throw new Exception("Adjunto no encontrado.");
    }
}

$pComentario = new PComentario();

$version_id = $_GET['version_id'] ?? 0;
$proyecto_id = $_GET['proyecto_id'] ?? 0;

if (!$version_id) {
    header("Location: /presentacion/versiones/");
    exit;
}

// Procesar Nuevo Comentario / Revisión
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkAccess(['Docente']);
    try {
        $pComentario->guardarComentario($_POST, $_FILES);
        // El método NComentario->insertar ya actualiza el estado del proyecto si se envía
        header("Location: /presentacion/comentarios/?proyecto_id=$proyecto_id&version_id=$version_id");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Procesar Descarga de Adjunto
if (isset($_GET['action']) && $_GET['action'] === 'descargar_adjunto') {
    $id = $_GET['id'] ?? 0;
    try {
        $pComentario->descargar($id);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$version = $pComentario->obtenerVersionPorId($version_id);
$comentarios = $pComentario->obtenerComentarios($version_id);

require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Revisión de Versión';</script>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Comentarios y Revisiones</h3>
        <p class="text-sm text-gray-400 mt-0.5">Versión v<?= $version['numero'] ?> del proyecto: <?= htmlspecialchars($version['nombre_proyecto']) ?></p>
    </div>
    <a href="/presentacion/versiones/?proyecto_id=<?= $proyecto_id ?>" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center space-x-1">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Volver al historial</span>
    </a>
</div>

<?php if (isset($error)): ?>
<div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 text-sm border border-red-100"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

    <!-- Listado de Comentarios -->
    <div class="space-y-6">
        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Línea de Tiempo</h4>
        
        <?php if (empty($comentarios)): ?>
            <div class="bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
                <i data-lucide="message-circle" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                <p class="text-gray-400 text-sm">No hay comentarios aún para esta versión.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach($comentarios as $c): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 relative">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                <?= substr($c['nombre_usuario'], 0, 1) ?>
                            </div>
                            <span class="text-sm font-semibold text-gray-700"><?= htmlspecialchars($c['nombre_usuario']) ?></span>
                        </div>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full bg-gray-100 text-gray-500"><?= $c['rol_usuario'] ?></span>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br(htmlspecialchars($c['mensaje'])) ?></p>
                    
                    <?php if ($c['ruta_archivo']): ?>
                        <div class="mt-3 pt-3 border-t border-gray-50">
                            <a href="?action=descargar_adjunto&id=<?= $c['id'] ?>&proyecto_id=<?= $proyecto_id ?>&version_id=<?= $version_id ?>" class="inline-flex items-center space-x-2 text-xs text-blue-600 hover:underline">
                                <i data-lucide="download" class="w-3 h-3"></i>
                                <span>Descargar adjunto de revisión</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulario de Revisión (Solo Docentes) -->
    <div class="lg:sticky lg:top-6 self-start">
        <?php if ($_SESSION['rol'] === 'Docente'): ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h4 class="text-base font-bold text-gray-800 mb-4 flex items-center space-x-2">
                <i data-lucide="clipboard-check" class="w-5 h-5 text-blue-600"></i>
                <span>Registrar Revisión</span>
            </h4>
            
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="version_id" value="<?= $version_id ?>">
                <input type="hidden" name="proyecto_id" value="<?= $proyecto_id ?>">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resultado de la Revisión</label>
                    <select name="estado" required class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="">-- Cambiar estado del proyecto --</option>
                        <option value="En Revisión">En Revisión (Continuar)</option>
                        <option value="Observado">Observado (Requiere cambios)</option>
                        <option value="Aprobado">Aprobado (Finalizar)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje / Observaciones</label>
                    <textarea name="mensaje" rows="5" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500" placeholder="Escribe aquí tus comentarios detallados..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Adjuntar Documento (Opcional)</label>
                    <input type="file" name="archivo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-50 file:text-gray-600">
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl shadow-lg shadow-blue-200 transition flex items-center justify-center space-x-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Enviar Revisión Sincrónica</span>
                </button>
            </form>
        </div>
        <?php else: ?>
        <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center text-gray-400">
            <i data-lucide="lock" class="w-10 h-10 mx-auto mb-3 opacity-20"></i>
            <p class="text-sm font-medium">Solo los docentes asignados pueden registrar revisiones en este proyecto.</p>
        </div>
        <?php endif; ?>
    </div>

</div>

</div>
</main>
</body>
<script>lucide.createIcons();</script>
</html>
