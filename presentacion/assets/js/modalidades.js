const API_URL = '/api/modalidades/';

let editandoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    listar();

    document.getElementById('btn-nueva-modalidad').addEventListener('click', abrirModalNuevo);
    document.getElementById('form-modalidad').addEventListener('submit', guardar);
    document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
});

async function listar() {
    try {
        const res = await api.get(API_URL);
        const tbody = document.getElementById('tabla-modalidades-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">No hay modalidades registradas.</td></tr>`;
            return;
        }

        res.data.forEach(m => {
            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono">${m.id}</td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium">${m.nombre}</td>
                    <td class="px-6 py-3 text-sm text-gray-500 max-w-xs truncate">${m.descripcion ?? ''}</td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(${m.id}, '${m.nombre.replace(/'/g, "\\'")}', '${(m.descripcion || '').replace(/'/g, "\\'").replace(/\n/g, "\\n")}')" 
                                class="text-blue-600 hover:text-blue-800 font-medium transition" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <button onclick="eliminar(${m.id})" 
                                class="text-red-500 hover:text-red-700 font-medium transition" title="Eliminar">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>`;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar modalidades:', err);
    }
}

async function guardar(e) {
    e.preventDefault();
    const nombre = document.getElementById('input-nombre').value.trim();
    const descripcion = document.getElementById('input-descripcion').value.trim();

    if (!nombre) return;

    try {
        await api.post(API_URL, { id: editandoId, nombre, descripcion });
        cerrarModal();
        listar();
    } catch (err) {
        console.error('Error al guardar modalidad:', err);
    }
}

function editar(id, nombre, descripcion) {
    editandoId = id;
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('input-descripcion').value = descripcion;
    document.getElementById('modal-titulo').textContent = 'Editar Modalidad';
    document.getElementById('modal-modalidad').classList.remove('hidden');
}

async function eliminar(id) {
    if (!confirm('¿Está seguro de eliminar esta modalidad?')) return;

    try {
        await api.delete(API_URL, { id });
        listar();
    } catch (err) {
        console.error('Error al eliminar modalidad:', err);
    }
}

function abrirModalNuevo() {
    editandoId = 0;
    document.getElementById('form-modalidad').reset();
    document.getElementById('modal-titulo').textContent = 'Nueva Modalidad';
    document.getElementById('modal-modalidad').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-modalidad').classList.add('hidden');
    document.getElementById('form-modalidad').reset();
    editandoId = 0;
}
