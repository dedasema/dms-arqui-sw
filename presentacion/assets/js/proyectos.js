const API_URL = '/api/proyectos/';

let editandoId = 0;

// Estados según máquina de estados del sistema (Fase 3, Sección C)
const ESTADOS = ['Iniciado', 'Asignado', 'En Revisión', 'Observado', 'Aprobado'];

document.addEventListener('DOMContentLoaded', () => {
    listar();
    cargarSelects();

    document.getElementById('btn-nuevo-proyecto').addEventListener('click', abrirModalNuevo);
    document.getElementById('form-proyecto').addEventListener('submit', guardar);
    document.getElementById('btn-cancelar').addEventListener('click', cerrarModal);
});

async function listar() {
    try {
        const res = await api.get(API_URL);
        const tbody = document.getElementById('tabla-proyectos-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-gray-400 text-sm">No hay proyectos registrados.</td></tr>`;
            return;
        }

        res.data.forEach(p => {
            const estadoBadge = {
                'Iniciado':    'bg-gray-100 text-gray-700',
                'Asignado':    'bg-blue-100 text-blue-700',
                'En Revisión': 'bg-yellow-100 text-yellow-700',
                'Observado':   'bg-orange-100 text-orange-700',
                'Aprobado':    'bg-green-100 text-green-700'
            }[p.estado] || 'bg-gray-100 text-gray-600';

            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-sm text-gray-600 font-mono">${p.id}</td>
                    <td class="px-6 py-3 text-sm text-gray-800 font-medium max-w-xs truncate">${p.titulo}</td>
                    <td class="px-6 py-3 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold ${estadoBadge}">${p.estado}</span>
                    </td>
                    <td class="px-6 py-3 text-sm text-gray-500">${p.nombre_carrera ?? '—'}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${p.nombre_modalidad}</td>
                    <td class="px-6 py-3 text-sm text-gray-500">${p.codigo_gestion}</td>
                    <td class="px-6 py-3 text-sm text-right space-x-2">
                        ${window.USER_ROLE === 'Administrador' ? `
                        <button onclick="editar(${p.id}, '${p.titulo.replace(/'/g, "\\'")}', '${p.estado}', ${p.carrera_id === null ? "''" : p.carrera_id}, ${p.modalidad_id}, ${p.gestion_id})" 
                                class="text-blue-600 hover:text-blue-800 transition" title="Editar">
                            <i data-lucide="pencil" class="w-4 h-4 inline"></i>
                        </button>
                        <button onclick="eliminar(${p.id})" 
                                class="text-red-500 hover:text-red-700 transition" title="Eliminar">
                            <i data-lucide="trash-2" class="w-4 h-4 inline"></i>
                        </button>
                        ` : window.USER_ROLE === 'Estudiante' ? `
                        <a href="/presentacion/versiones/?proyecto_id=${p.id}" 
                           class="text-indigo-600 hover:text-indigo-800 transition font-semibold" title="Versiones Documentales">
                            <i data-lucide="upload-cloud" class="w-4 h-4 inline mr-1"></i> Subir / Ver Avances
                        </a>
                        ` : window.USER_ROLE === 'Docente' ? `
                        <a href="/presentacion/versiones/?proyecto_id=${p.id}" 
                           class="text-blue-600 hover:text-blue-800 transition font-semibold" title="Revisar Avances">
                            <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i> Ver Avances
                        </a>
                        ` : ''}
                    </td>
                </tr>`;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar proyectos:', err);
    }
}

async function cargarSelects() {
    try {
        const [carreras, modalidades, gestiones] = await Promise.all([
            api.get('/api/carreras/'),
            api.get('/api/modalidades/'),
            api.get('/api/gestiones/')
        ]);

        const selectCarrera = document.getElementById('select-carrera');
        selectCarrera.innerHTML = '<option value="">Opcional / Ninguna...</option>';
        carreras.data.forEach(c => {
            selectCarrera.innerHTML += `<option value="${c.id}">${c.nombre} (${c.sigla})</option>`;
        });

        const selectModalidad = document.getElementById('select-modalidad');
        selectModalidad.innerHTML = '<option value="">Seleccionar modalidad...</option>';
        modalidades.data.forEach(m => {
            selectModalidad.innerHTML += `<option value="${m.id}">${m.nombre}</option>`;
        });

        const selectGestion = document.getElementById('select-gestion');
        selectGestion.innerHTML = '<option value="">Seleccionar gestión...</option>';
        gestiones.data.forEach(g => {
            selectGestion.innerHTML += `<option value="${g.id}">${g.codigo} (${g.fecha_inicio} — ${g.fecha_fin})</option>`;
        });
    } catch (err) {
        console.error('Error al cargar selects:', err);
    }
}

async function guardar(e) {
    e.preventDefault();
    const titulo       = document.getElementById('input-titulo').value.trim();
    const estado       = document.getElementById('select-estado').value;
    const carrera_id   = document.getElementById('select-carrera').value;
    const modalidad_id = document.getElementById('select-modalidad').value;
    const gestion_id   = document.getElementById('select-gestion').value;

    if (!titulo || !estado || !modalidad_id || !gestion_id) return;

    try {
        await api.post(API_URL, { id: editandoId, titulo, estado, carrera_id, modalidad_id, gestion_id });
        cerrarModal();
        listar();
    } catch (err) {
        console.error('Error al guardar proyecto:', err);
    }
}

function editar(id, titulo, estado, carrera_id, modalidad_id, gestion_id) {
    editandoId = id;
    document.getElementById('input-titulo').value     = titulo;
    document.getElementById('select-estado').value    = estado;
    document.getElementById('select-carrera').value   = carrera_id;
    document.getElementById('select-modalidad').value = modalidad_id;
    document.getElementById('select-gestion').value   = gestion_id;
    document.getElementById('modal-titulo').textContent = 'Editar Proyecto';
    document.getElementById('modal-proyecto').classList.remove('hidden');
}

async function eliminar(id) {
    if (!confirm('¿Está seguro de eliminar este proyecto?')) return;
    try {
        await api.delete(API_URL, { id });
        listar();
    } catch (err) {
        console.error('Error al eliminar proyecto:', err);
    }
}

function abrirModalNuevo() {
    editandoId = 0;
    document.getElementById('form-proyecto').reset();
    document.getElementById('modal-titulo').textContent = 'Nuevo Proyecto';
    document.getElementById('modal-proyecto').classList.remove('hidden');
}

function cerrarModal() {
    document.getElementById('modal-proyecto').classList.add('hidden');
    document.getElementById('form-proyecto').reset();
    editandoId = 0;
}
