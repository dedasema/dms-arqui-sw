<?php
require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Gestionar Proyectos';</script>

<!-- Header de sección -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Listado de Proyectos de Titulación</h3>
        <p class="text-sm text-gray-400 mt-0.5">Gestiona los proyectos de grado registrados en el sistema.</p>
    </div>
    <button id="btn-nuevo-proyecto"
            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-lg shadow-sm transition flex items-center space-x-2">
        <i data-lucide="plus" class="w-4 h-4"></i>
        <span>Nuevo Proyecto</span>
    </button>
</div>

<!-- Tabla -->
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
        <tbody id="tabla-proyectos-body">
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal-proyecto" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 id="modal-titulo" class="text-lg font-semibold text-gray-800">Nuevo Proyecto</h3>
        </div>
        <form id="form-proyecto" class="p-6 space-y-4">
            <div>
                <label for="input-titulo" class="block text-sm font-medium text-gray-700 mb-1">Título del Proyecto</label>
                <input type="text" id="input-titulo" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                       placeholder="Ej: Sistema de Gestión de Inventarios">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="select-estado" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select id="select-estado" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                        <option value="Iniciado">Iniciado</option>
                        <option value="Asignado">Asignado</option>
                        <option value="En Revisión">En Revisión</option>
                        <option value="Observado">Observado</option>
                        <option value="Aprobado">Aprobado</option>
                    </select>
                </div>
                <div>
                    <label for="select-carrera" class="block text-sm font-medium text-gray-700 mb-1">Carrera</label>
                    <select id="select-carrera"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                        <option value="">Cargando...</option>
                    </select>
                </div>
                <div>
                    <label for="select-modalidad" class="block text-sm font-medium text-gray-700 mb-1">Modalidad</label>
                    <select id="select-modalidad" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                        <option value="">Cargando...</option>
                    </select>
                </div>
                <div>
                    <label for="select-gestion" class="block text-sm font-medium text-gray-700 mb-1">Gestión</label>
                    <select id="select-gestion" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                        <option value="">Cargando...</option>
                    </select>
                </div>
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
<script src="/presentacion/assets/js/proyectos.js"></script>
<script>lucide.createIcons();</script>
</html>
