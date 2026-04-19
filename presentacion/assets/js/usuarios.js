const API_URL = '/api/usuarios/';
const API_CARRERAS_URL = '/api/carreras/';

let editandoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    listar();
    cargarCarreras();

    document.getElementById('btn-nuevo-usuario').addEventListener('click', abrirModalNuevo);
    document.getElementById('form-usuario').addEventListener('submit', guardar);
    document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
});

async function listar() {
    try {
        const res = await api.get(API_URL);
        const tbody = document.getElementById('tabla-usuarios-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">No hay usuarios registrados.</td></tr>`;
            return;
        }

        res.data.forEach(u => {
            const rolBadge = {
                'Administrador': 'bg-purple-100 text-purple-700',
                'Docente': 'bg-blue-100 text-blue-700',
                'Estudiante': 'bg-green-100 text-green-700'
            }[u.rol] || 'bg-gray-100 text-gray-600';

            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono">${u.id}</td>
                    <td class="px-6 py-3 text-sm text-gray-600">${u.codigo ?? '—'}</td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium">${u.nombre_completo}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${u.correo}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${rolBadge}">${u.rol}</span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500">${u.nombre_carrera ?? '—'}</td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        <button onclick="editar(${u.id}, '${u.nombre_completo.replace(/'/g, "\\'")}', '${u.correo.replace(/'/g, "\\'")}', '${u.rol}', '${(u.codigo ?? '').replace(/'/g, "\\'")}', ${u.carrera_id})" 
                                class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <button onclick="eliminar(${u.id})" 
                                class="text-red-500 hover:text-red-700 transition" title="Eliminar">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                    </td>
                </tr>`;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar usuarios:', err);
    }
}

async function cargarCarreras() {
    try {
        const res = await api.get(API_CARRERAS_URL);
        const select = document.getElementById('select-carrera');
        select.innerHTML = '<option value="">Seleccionar carrera...</option>';
        res.data.forEach(c => {
            select.innerHTML += `<option value="${c.id}">${c.nombre} (${c.sigla})</option>`;
        });
    } catch (err) {
        console.error('Error al cargar carreras:', err);
    }
}

async function guardar(e) {
    e.preventDefault();
    const nombre_completo = document.getElementById('input-nombre').value.trim();
    const correo          = document.getElementById('input-correo').value.trim();
    const contrasena      = document.getElementById('input-contrasena').value;
    const rol             = document.getElementById('select-rol').value;
    const codigo          = document.getElementById('input-codigo').value.trim();
    const carrera_id      = document.getElementById('select-carrera').value;

    if (!nombre_completo || !correo || !rol || !carrera_id) return;

    try {
        await api.post(API_URL, { id: editandoId, nombre_completo, correo, contrasena, rol, codigo, carrera_id });
        cerrarModal();
        listar();
    } catch (err) {
        console.error('Error al guardar usuario:', err);
    }
}

function editar(id, nombre_completo, correo, rol, codigo, carrera_id) {
    editandoId = id;
    document.getElementById('input-nombre').value    = nombre_completo;
    document.getElementById('input-correo').value    = correo;
    document.getElementById('input-contrasena').value = ''; // No revelar hash
    document.getElementById('select-rol').value      = rol;
    document.getElementById('input-codigo').value    = codigo;
    document.getElementById('select-carrera').value  = carrera_id;
    document.getElementById('label-contrasena').textContent = 'Nueva Contraseña (dejar vacío para no cambiar)';
    document.getElementById('modal-titulo').textContent = 'Editar Usuario';
    document.getElementById('modal-usuario').classList.remove('hidden');
}

async function eliminar(id) {
    if (!confirm('¿Está seguro de eliminar este usuario?')) return;
    try {
        await api.delete(API_URL, { id });
        listar();
    } catch (err) {
        console.error('Error al eliminar usuario:', err);
    }
}

function abrirModalNuevo() {
    editandoId = 0;
    document.getElementById('form-usuario').reset();
    document.getElementById('label-contrasena').textContent = 'Contraseña';
    document.getElementById('modal-titulo').textContent = 'Nuevo Usuario';
    document.getElementById('modal-usuario').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-usuario').classList.add('hidden');
    document.getElementById('form-usuario').reset();
    editandoId = 0;
}
