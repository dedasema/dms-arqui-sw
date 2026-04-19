<?php
require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Modalidades';</script>

<!-- Header de sección -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Modalidades de Titulación</h3>
        <p class="text-sm text-gray-400 mt-0.5">Administra las modalidades disponibles para los proyectos de grado.</p>
    </div>
    <button id="btn-nueva-modalidad"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Nueva Modalidad</span>
    </button>
</div>

<!-- Tabla -->
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
        <tbody id="tabla-modalidades-body">
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal-modalidad" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nueva Modalidad</h3>
        </div>
        <form id="form-modalidad" class="p-6 space-y-4">
            <div>
                <label for="input-nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" id="input-nombre" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       placeholder="Ej: Trabajo Dirigido">
            </div>
            <div>
                <label for="input-descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="input-descripcion" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none"
                          placeholder="Descripción de la modalidad..."></textarea>
            </div>
            <div class="flex justify-end space-x-3 pt-2">
                <button type="button" id="btn-cancelar"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                    Cancelar
                </button>
                <button type="submit"
                        class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

        </div><!-- Cierre del contenedor principal del layout -->
    </main>
</body>
<script src="/presentacion/assets/js/api.js"></script>
<script src="/presentacion/assets/js/modalidades.js"></script>
<script>lucide.createIcons();</script>
</html>
