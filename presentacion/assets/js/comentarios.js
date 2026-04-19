const API_COMENTARIOS_URL = '/api/comentarios/';
const API_DESCARGAR_URL   = '/api/comentarios/descargar.php';

let proyectoSeleccionadoId = 0;
let versionSeleccionadaId = 0;

document.addEventListener('DOMContentLoaded', () => {
    cargarProyectos();
    
    document.getElementById('select-proyecto').addEventListener('change', onProyectoCambiado);
    document.getElementById('select-version').addEventListener('change', onVersionCambiada);
    document.getElementById('form-comentario').addEventListener('submit', enviarComentario);
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

async function onProyectoCambiado() {
    proyectoSeleccionadoId = parseInt(this.value) || 0;
    const selectVer = document.getElementById('select-version');
    selectVer.innerHTML = '<option value="">-- Seleccionar versión --</option>';
    document.getElementById('lista-comentarios').innerHTML = '<p class="text-gray-400 text-sm py-4 text-center">Selecciona un proyecto y versión.</p>';
    versionSeleccionadaId = 0;

    if (!proyectoSeleccionadoId) return;

    try {
        const res = await api.get(`/api/versiones/?proyecto_id=${proyectoSeleccionadoId}`);
        res.data.forEach(v => {
            selectVer.innerHTML += `<option value="${v.id}">v${v.numero} - ${v.nombre}</option>`;
        });
    } catch (err) {
        console.error('Error al cargar versiones:', err);
    }
}

function onVersionCambiada() {
    versionSeleccionadaId = parseInt(this.value) || 0;
    
    if (versionSeleccionadaId) {
        listarComentarios();
    } else {
        document.getElementById('lista-comentarios').innerHTML = '<p class="text-gray-400 text-sm py-4 text-center">Selecciona una versión para ver comentarios.</p>';
    }
}

async function listarComentarios() {
    try {
        const res = await api.get(`${API_COMENTARIOS_URL}?version_id=${versionSeleccionadaId}`);
        const contenedor = document.getElementById('lista-comentarios');
        contenedor.innerHTML = '';

        if (res.data.length === 0) {
            contenedor.innerHTML = `<p class="text-center text-gray-400 text-sm py-6">No hay comentarios para esta versión aún.</p>`;
            return;
        }

        res.data.forEach(c => {
            const adjuntoHTML = c.ruta_archivo 
                ? `<div class="mt-2"><a href="${API_DESCARGAR_URL}?id=${c.id}" class="inline-flex items-center space-x-1 text-xs text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded-md transition"><i data-lucide="paperclip" class="w-3 h-3"></i><span>Descargar adjunto</span></a></div>`
                : '';
                
            contenedor.innerHTML += `
                <div class="mb-4 bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-sm text-gray-800">${c.nombre_usuario}</span>
                        <span class="text-xs text-gray-400 bg-gray-200 px-2 py-0.5 rounded-full">${c.rol_usuario}</span>
                    </div>
                    <p class="text-sm text-gray-600 whitespace-pre-wrap">${c.mensaje}</p>
                    ${adjuntoHTML}
                </div>
            `;
        });

        if (window.lucide) lucide.createIcons();
    } catch (err) {
        console.error('Error al listar comentarios:', err);
    }
}

async function enviarComentario(e) {
    e.preventDefault();

    if (!proyectoSeleccionadoId || !versionSeleccionadaId) {
        alert('Debes seleccionar un proyecto y una versión.');
        return;
    }

    const mensaje = document.getElementById('input-mensaje').value.trim();
    const inputArchivo = document.getElementById('input-archivo');
    const nuevoEstado = document.getElementById('select-estado').value;
    
    if (!mensaje) {
        alert('Por favor, ingresa un mensaje.');
        return;
    }

    const formData = new FormData();
    formData.append('proyecto_id', proyectoSeleccionadoId);
    formData.append('version_id', versionSeleccionadaId);
    formData.append('mensaje', mensaje);
    
    if (nuevoEstado) {
        formData.append('nuevo_estado', nuevoEstado);
    }

    if (inputArchivo.files && inputArchivo.files.length > 0) {
        formData.append('archivo', inputArchivo.files[0]);
    }

    const btnEnviar = document.getElementById('btn-enviar');
    btnEnviar.disabled = true;
    btnEnviar.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i><span>Enviando...</span>';
    if (window.lucide) lucide.createIcons();

    try {
        const response = await fetch(API_COMENTARIOS_URL, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        if (result.status) {
            document.getElementById('input-mensaje').value = '';
            inputArchivo.value = '';
            document.getElementById('select-estado').value = '';
            listarComentarios();
            
            if(nuevoEstado) {
                const selectElement = document.getElementById('select-proyecto');
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                if(selectedOption && selectedOption.textContent) {
                    const idx = selectedOption.textContent.indexOf(']');
                    if (idx !== -1) {
                        const titulo = selectedOption.textContent.substring(idx + 2);
                        selectedOption.textContent = `[${nuevoEstado}] ${titulo}`;
                    }
                }
            }
        } else {
            alert('Error al enviar: ' + (result.error || 'Desconocido'));
        }
    } catch (err) {
        console.error('Error al enviar comentario:', err);
        alert('Error de conexión al enviar el comentario.');
    } finally {
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i><span>Registrar Revisión</span>';
        if (window.lucide) lucide.createIcons();
    }
}
