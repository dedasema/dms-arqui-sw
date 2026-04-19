const API_URL = '/api/carreras/';

// Variables de estado del módulo
let editandoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    listar();

    document.getElementById('btn-nueva-carrera').addEventListener('click', abrirModalNuevo);
    document.getElementById('form-carrera').addEventListener('submit', guardar);
    document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
});

async function listar() {
    try {
        const res = await api.get(API_URL);
        const tbody = document.getElementById('tabla-carreras-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No hay carreras registradas.</td></tr>`;
            return;
        }

        res.data.forEach(c => {
            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono">${c.id}</td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium">${c.nombre}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${c.sigla}</td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(${c.id}, '${c.nombre.replace(/'/g, "\\'")}', '${c.sigla.replace(/'/g, "\\'")}')" 
                                class="text-blue-600 hover:text-blue-800 font-medium transition" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <button onclick="eliminar(${c.id})" 
                                class="text-red-500 hover:text-red-700 font-medium transition" title="Eliminar">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>`;
        });

        // Re-renderizar iconos Lucide para los botones inyectados
        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar carreras:', err);
    }
}

async function guardar(e) {
    e.preventDefault();
    const nombre = document.getElementById('input-nombre').value.trim();
    const sigla = document.getElementById('input-sigla').value.trim();

    if (!nombre || !sigla) return;

    try {
        await api.post(API_URL, { id: editandoId, nombre, sigla });
        cerrarModal();
        listar();
    } catch (err) {
        console.error('Error al guardar carrera:', err);
    }
}

function editar(id, nombre, sigla) {
    editandoId = id;
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('input-sigla').value = sigla;
    document.getElementById('modal-titulo').textContent = 'Editar Carrera';
    document.getElementById('modal-carrera').classList.remove('hidden');
}

async function eliminar(id) {
    if (!confirm('¿Está seguro de eliminar esta carrera?')) return;

    try {
        await api.delete(API_URL, { id });
        listar();
    } catch (err) {
        console.error('Error al eliminar carrera:', err);
    }
}

function abrirModalNuevo() {
    editandoId = 0;
    document.getElementById('form-carrera').reset();
    document.getElementById('modal-titulo').textContent = 'Nueva Carrera';
    document.getElementById('modal-carrera').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-carrera').classList.add('hidden');
    document.getElementById('form-carrera').reset();
    editandoId = 0;
}
