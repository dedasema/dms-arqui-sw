const API_URL = '/api/asignaciones/';

// Roles de asignación por proyecto (distintos al rol de sistema del usuario)
const ROLES_ASIGNACION = ['Tutor', 'Revisor', 'Tribunal', 'Estudiante'];

let proyectoSeleccionadoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    cargarProyectos();
    cargarUsuarios();

    document.getElementById('select-proyecto').addEventListener('change', onProyectoCambiado);
    document.getElementById('btn-guardar').addEventListener('click', guardarAsignaciones);
});

async function cargarProyectos() {
    try {
        const res = await api.get('/api/proyectos/');
        const select = document.getElementById('select-proyecto');
        select.innerHTML = '<option value="">-- Seleccionar proyecto --</option>';
        res.data.forEach(p => {
            select.innerHTML += `<option value="${p.id}">[${p.estado}] ${p.titulo}</option>`;
        });
    } catch (err) {
        console.error('Error al cargar proyectos:', err);
    }
}

async function cargarUsuarios() {
    try {
        const res = await api.get('/api/usuarios/');
        const contenedor = document.getElementById('lista-usuarios');
        contenedor.innerHTML = '';

        res.data.forEach(u => {
            // Generar un row de usuario con su select de rol de asignación
            const opciones = ROLES_ASIGNACION.map(r => `<option value="${r}">${r}</option>`).join('');
            contenedor.innerHTML += `
                <div class="flex items-center justify-between py-2.5 px-3 rounded-lg border border-gray-100 hover:border-blue-200 hover:bg-blue-50/40 transition" data-usuario-id="${u.id}">
                    <label class="flex items-center space-x-3 cursor-pointer flex-1">
                        <input type="checkbox" class="chk-usuario w-4 h-4 accent-blue-600 rounded" value="${u.id}" data-nombre="${u.nombre_completo}">
                        <span class="text-sm font-medium text-gray-700">${u.nombre_completo}</span>
                        <span class="text-xs text-gray-400">${u.correo}</span>
                    </label>
                    <select class="sel-rol border border-gray-200 rounded-md px-2 py-1 text-xs text-gray-600 bg-white focus:ring-1 focus:ring-blue-400 outline-none">
                        ${opciones}
                    </select>
                </div>`;
        });
    } catch (err) {
        console.error('Error al cargar usuarios:', err);
    }
}

async function onProyectoCambiado() {
    proyectoSeleccionadoId = parseInt(this.value) || 0;

    // Desmarcar todos los checkboxes
    document.querySelectorAll('.chk-usuario').forEach(c => c.checked = false);

    if (!proyectoSeleccionadoId) {
        document.getElementById('tabla-asignaciones-body').innerHTML =
            `<tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 text-sm">Selecciona un proyecto.</td></tr>`;
        return;
    }

    await cargarAsignacionesActuales(proyectoSeleccionadoId);
}

async function cargarAsignacionesActuales(proyecto_id) {
    try {
        const res = await api.get(`${API_URL}?proyecto_id=${proyecto_id}`);
        const tbody = document.getElementById('tabla-asignaciones-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="px-4 py-6 text-center text-gray-400 text-sm">Sin asignaciones para este proyecto.</td></tr>`;
            return;
        }

        res.data.forEach(a => {
            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-4 py-2.5 text-sm text-gray-800">${a.nombre_completo}</td>
                    <td class="px-4 py-2.5 text-sm text-gray-500">${a.correo}</td>
                    <td class="px-4 py-2.5 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">${a.rol}</span>
                    </td>
                </tr>`;

            // Pre-marcar checkbox del usuario ya asignado
            const chk = document.querySelector(`.chk-usuario[value="${a.usuario_id}"]`);
            const selRol = chk?.closest('[data-usuario-id]')?.querySelector('.sel-rol');
            if (chk) {
                chk.checked = true;
                if (selRol) selRol.value = a.rol;
            }
        });
    } catch (err) {
        console.error('Error al cargar asignaciones:', err);
    }
}

async function guardarAsignaciones() {
    if (!proyectoSeleccionadoId) {
        alert('Selecciona un proyecto antes de guardar.');
        return;
    }

    const usuarios = [];
    document.querySelectorAll('.chk-usuario:checked').forEach(chk => {
        const row = chk.closest('[data-usuario-id]');
        const rol = row.querySelector('.sel-rol').value;
        usuarios.push({ usuario_id: parseInt(chk.value), rol });
    });

    try {
        await api.post(API_URL, { proyecto_id: proyectoSeleccionadoId, usuarios });
        await cargarAsignacionesActuales(proyectoSeleccionadoId);
    } catch (err) {
        console.error('Error al guardar asignaciones:', err);
    }
}
