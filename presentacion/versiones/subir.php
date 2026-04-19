<?php
require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Versiones Documentales';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Historial de Versiones</h3>
    <p class="text-sm text-gray-400 mt-0.5">Sube y consulta los avances documentales de cada proyecto.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Panel izquierdo: Subida de archivo -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col space-y-4 lg:col-span-1">
        <h4 class="text-sm font-semibold text-gray-700 flex items-center space-x-2">
            <i data-lucide="upload" class="w-4 h-4 text-blue-500"></i>
            <span>Subir Avance</span>
        </h4>

        <form id="form-subir" class="space-y-4">
            <div>
                <label for="select-proyecto" class="block text-sm font-medium text-gray-700 mb-1">Proyecto</label>
                <select id="select-proyecto"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                    <option value="">-- Cargando proyectos --</option>
                </select>
            </div>

            <div>
                <label for="input-archivo" class="block text-sm font-medium text-gray-700 mb-1">Documento</label>
                <input type="file" id="input-archivo" accept=".pdf,.docx,.doc"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-400 mt-1">Formatos aceptados: PDF, DOCX, DOC</p>
            </div>

            <button type="submit" id="btn-subir"
                    class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition flex items-center justify-center space-x-2">
                <i data-lucide="upload-cloud" class="w-4 h-4"></i>
                <span>Subir Avance</span>
            </button>
        </form>
    </div>

    <!-- Panel derecho: Historial de versiones -->
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
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamaño</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Subido por</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Descargar</th>
                </tr>
            </thead>
            <tbody id="tabla-versiones-body">
                <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">Selecciona un proyecto para ver su historial.</td></tr>
            </tbody>
        </table>
    </div>

</div>

        </div><!-- Cierre del contenedor principal del layout -->
    </main>
</body>
<script src="/presentacion/assets/js/api.js"></script>
<script src="/presentacion/assets/js/versiones.js"></script>
<script>lucide.createIcons();</script>
</html>
