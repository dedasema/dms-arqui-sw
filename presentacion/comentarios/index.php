<?php
require_once __DIR__ . '/../componentes/layout.php';
?>

<script>document.getElementById('page-title').textContent = 'Comentarios y Revisiones';</script>

<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Comentarios y Revisiones</h3>
    <p class="text-sm text-gray-400 mt-0.5">Revisa versiones documentales, añade comentarios y cambia el estado del proyecto.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Panel izquierdo: Carga y Formulario -->
    <div class="space-y-6">
        <!-- Selector -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 space-y-4">
            <div>
                <label for="select-proyecto" class="block text-sm font-medium text-gray-700 mb-1">Proyecto de Titulación</label>
                <select id="select-proyecto" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                    <option value="">-- Cargando proyectos --</option>
                </select>
            </div>
            
            <div>
                <label for="select-version" class="block text-sm font-medium text-gray-700 mb-1">Versión a Evaluar</label>
                <select id="select-version" class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                    <option value="">-- Seleccionar primero un proyecto --</option>
                </select>
            </div>
        </div>
        
        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <h4 class="text-sm font-semibold text-gray-700 flex items-center space-x-2 mb-4">
                <i data-lucide="message-square" class="w-4 h-4 text-blue-500"></i>
                <span>Nuevo Comentario / Decisión</span>
            </h4>
            
            <form id="form-comentario" class="space-y-4">
                <div>
                    <label for="input-mensaje" class="block text-sm font-medium text-gray-700 mb-1">Comentario</label>
                    <textarea id="input-mensaje" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition resize-none" placeholder="Escribe tus observaciones o correcciones aquí..." required></textarea>
                </div>
                
                <div>
                    <label for="select-estado" class="block text-sm font-medium text-gray-700 mb-1">Dictamen / Cambio de Estado (Opcional)</label>
                    <select id="select-estado" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition bg-white">
                        <option value="">-- Sin cambio de estado --</option>
                        <option value="Observado">Observado (Requiere Cambios)</option>
                        <option value="En Revisión">En Revisión (Continuar Análisis)</option>
                        <option value="Aprobado">Aprobado (Documento Final)</option>
                    </select>
                </div>
                
                <div>
                    <label for="input-archivo" class="block text-sm font-medium text-gray-700 mb-1">Documento Corregido (Opcional)</label>
                    <input type="file" id="input-archivo" accept=".pdf,.docx,.doc" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition cursor-pointer file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <button type="submit" id="btn-enviar" class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 text-white text-sm font-semibold py-2.5 rounded-lg shadow-sm transition flex items-center justify-center space-x-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span>Registrar Revisión</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Panel derecho: Historial de Comentarios -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full lg:max-h-[800px]">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center space-x-2 bg-gray-50 flex-shrink-0">
            <i data-lucide="list-collapse" class="w-4 h-4 text-gray-500"></i>
            <h4 class="text-sm font-semibold text-gray-700">Línea de Vida - Evaluación</h4>
        </div>
        
        <!-- Área scrolleable de comentarios -->
        <div id="lista-comentarios" class="flex-1 overflow-y-auto p-5 bg-white">
            <p class="text-gray-400 text-sm py-4 text-center">Selecciona un proyecto y versión.</p>
        </div>
    </div>

</div>

        </div><!-- Cierre del contenedor principal del layout -->
    </main>
</body>
<script src="/presentacion/assets/js/api.js"></script>
<script src="/presentacion/assets/js/comentarios.js"></script>
<script>lucide.createIcons();</script>
</html>
