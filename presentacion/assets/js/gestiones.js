const API_URL = '/api/gestiones/';

let editandoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    listar();

    document.getElementById('btn-nueva-gestion').addEventListener('click', abrirModalNuevo);
    document.getElementById('form-gestion').addEventListener('submit', guardar);
    document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
});

async function listar() {
    try {
        const res = await api.get(API_URL);
        const tbody = document.getElementById('tabla-gestiones-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No hay gestiones registradas.</td></tr>`;
            return;
        }

        res.data.forEach(g => {
            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono">${g.id}</td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium">${g.codigo}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${g.fecha_inicio}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${g.fecha_fin}</td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(${g.id}, '${g.codigo}', '${g.fecha_inicio}', '${g.fecha_fin}')" 
                                class="text-blue-600 hover:text-blue-800 font-medium transition" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <button onclick="eliminar(${g.id})" 
                                class="text-red-500 hover:text-red-700 font-medium transition" title="Eliminar">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>`;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar gestiones:', err);
    }
}

async function guardar(e) {
    e.preventDefault();
    const codigo = document.getElementById('input-codigo').value.trim();
    const fecha_inicio = document.getElementById('input-fecha-inicio').value;
    const fecha_fin = document.getElementById('input-fecha-fin').value;

    if (!codigo || !fecha_inicio || !fecha_fin) return;

    try {
        await api.post(API_URL, { id: editandoId, codigo, fecha_inicio, fecha_fin });
        cerrarModal();
        listar();
    } catch (err) {
        console.error('Error al guardar gestión:', err);
    }
}

function editar(id, codigo, fecha_inicio, fecha_fin) {
    editandoId = id;
    document.getElementById('input-codigo').value = codigo;
    document.getElementById('input-fecha-inicio').value = fecha_inicio;
    document.getElementById('input-fecha-fin').value = fecha_fin;
    document.getElementById('modal-titulo').textContent = 'Editar Gestión';
    document.getElementById('modal-gestion').classList.remove('hidden');
}

async function eliminar(id) {
    if (!confirm('¿Está seguro de eliminar esta gestión académica?')) return;

    try {
        await api.delete(API_URL, { id });
        listar();
    } catch (err) {
        console.error('Error al eliminar gestión:', err);
    }
}

function abrirModalNuevo() {
    editandoId = 0;
    document.getElementById('form-gestion').reset();
    document.getElementById('modal-titulo').textContent = 'Nueva Gestión';
    document.getElementById('modal-gestion').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-gestion').classList.add('hidden');
    document.getElementById('form-gestion').reset();
    editandoId = 0;
}
