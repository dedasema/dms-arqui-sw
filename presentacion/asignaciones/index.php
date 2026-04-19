<?php
require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Asignaciones';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Asignación de Tribunal y Tutores</h3>
    <p class="text-sm text-gray-400 mt-0.5">Asigna usuarios con sus roles a cada proyecto de titulación.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Panel izquierdo: Selector de proyecto + lista de usuarios -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex flex-col space-y-4">
        <div>
            <label for="select-proyecto" class="block text-sm font-medium text-gray-700 mb-1">Proyecto</label>
            <select id="select-proyecto"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                <option value="">-- Cargando proyectos --</option>
            </select>
        </div>

        <div>
            <p class="text-sm font-medium text-gray-700 mb-2">Usuarios disponibles</p>
            <div id="lista-usuarios" class="space-y-1.5 max-h-80 overflow-y-auto pr-1">
                <p class="text-sm text-gray-400 text-center py-4">Cargando usuarios...</p>
            </div>
        </div>

        <button id="btn-guardar"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition flex items-center justify-center space-x-2">
            <i data-lucide="save" class="w-4 h-4"></i>
            <span>Guardar Asignaciones</span>
        </button>
    </div>

    <!-- Panel derecho: Asignaciones actuales del proyecto seleccionado -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h4 class="text-sm font-semibold text-gray-700">Asignaciones actuales</h4>
            <p class="text-xs text-gray-400 mt-0.5">Tribunal y tutores asignados al proyecto seleccionado.</p>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Correo</th>
                    <th class="px-4 py-2.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rol</th>
                </tr>
            </thead>
            <tbody id="tabla-asignaciones-body">
                <tr><td colspan="3" class="px-4 py-6 text-center text-gray-400 text-sm">Selecciona un proyecto.</td></tr>
            </tbody>
        </table>
    </div>

</div>

        </div><!-- Cierre del contenedor principal del layout -->
    </main>
</body>
<script src="/presentacion/assets/js/api.js"></script>
<script src="/presentacion/assets/js/asignaciones.js"></script>
<script>lucide.createIcons();</script>
</html>
