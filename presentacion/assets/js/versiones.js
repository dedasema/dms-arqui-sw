const API_VERSIONES_URL = '/api/versiones/';
const API_SUBIR_URL     = '/api/versiones/subir.php';
const API_DESCARGAR_URL = '/api/versiones/descargar.php';

let proyectoSeleccionadoId = 0;

document.addEventListener('DOMContentLoaded', () => {
    cargarProyectos();

    document.getElementById('select-proyecto').addEventListener('change', onProyectoCambiado);
    document.getElementById('form-subir').addEventListener('submit', subirArchivo);
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

function onProyectoCambiado() {
    proyectoSeleccionadoId = parseInt(this.value) || 0;
    if (proyectoSeleccionadoId) {
        listarHistorial(proyectoSeleccionadoId);
    } else {
        document.getElementById('tabla-versiones-body').innerHTML =
            `<tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">Selecciona un proyecto.</td></tr>`;
    }
}

async function listarHistorial(proyecto_id) {
    try {
        const res = await api.get(`${API_VERSIONES_URL}?proyecto_id=${proyecto_id}`);
        const tbody = document.getElementById('tabla-versiones-body');
        tbody.innerHTML = '';

        if (res.data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-4 py-6 text-center text-gray-400 text-sm">Sin versiones para este proyecto.</td></tr>`;
            return;
        }

        res.data.forEach(v => {
            const pesoKB = (v.peso_bytes / 1024).toFixed(1);
            tbody.innerHTML += `
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="px-4 py-2.5 text-sm font-mono text-gray-600">v${v.numero}</td>
                    <td class="px-4 py-2.5 text-sm text-gray-800 font-medium max-w-xs truncate">${v.nombre}</td>
                    <td class="px-4 py-2.5 text-sm text-gray-500">${pesoKB} KB</td>
                    <td class="px-4 py-2.5 text-sm text-gray-500">${v.nombre_usuario}</td>
                    <td class="px-4 py-2.5 text-sm">
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">${v.estado}</span>
                    </td>
                    <td class="px-4 py-2.5 text-sm text-right">
                        <a href="${API_DESCARGAR_URL}?id=${v.id}" 
                           class="text-blue-600 hover:text-blue-800 font-medium transition flex items-center justify-end space-x-1" 
                           title="Descargar">
                            <i data-lucide="download" class="w-4 h-4 inline"></i>
                            <span>Descargar</span>
                        </a>
                    </td>
                </tr>`;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar historial:', err);
    }
}

async function subirArchivo(e) {
    e.preventDefault();

    if (!proyectoSeleccionadoId) {
        alert('Selecciona un proyecto antes de subir.');
        return;
    }

    const input = document.getElementById('input-archivo');
    if (!input.files || input.files.length === 0) {
        alert('Selecciona un archivo para subir.');
        return;
    }

    const formData = new FormData();
    formData.append('proyecto_id', proyectoSeleccionadoId);
    formData.append('archivo', input.files[0]);

    const btnSubir = document.getElementById('btn-subir');
    btnSubir.disabled = true;
    btnSubir.textContent = 'Subiendo...';

    try {
        // FormData requiere fetch nativo (api.js fuerza Content-Type: application/json)
        const response = await fetch(API_SUBIR_URL, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (result.status) {
            input.value = '';
            listarHistorial(proyectoSeleccionadoId);
        } else {
            alert('Error al subir: ' + (result.error || 'Desconocido'));
        }
    } catch (err) {
        console.error('Error al subir archivo:', err);
        alert('Error de conexión al subir el archivo.');
    } finally {
        btnSubir.disabled = false;
        btnSubir.textContent = 'Subir Avance';
    }
}
