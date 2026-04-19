# Fase 9: Especificación Técnica - CU6 Administrar Versiones Documentales

## 1. Capa de Datos (`datos/DVersion.php`)
Gestiona la persistencia de los archivos y metadatos en la tabla `version_documental`.

* **Método `obtenerUltimoNumero($proyecto_id)`**:
    * **SQL**: `SELECT COALESCE(MAX(numero), 0) FROM version_documental WHERE proyecto_id = ?;`
    * **Acción**: Retorna el número de la última versión para calcular la siguiente (+1).
* **Método `subir($nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id, $estado, $numero)`**:
    * **SQL**: `INSERT INTO version_documental (nombre, peso_bytes, proyecto_id, ruta_archivo, usuario_id, estado, numero) VALUES (?, ?, ?, ?, ?, ?, ?) RETURNING id;`
    * **Acción**: Registra la nueva versión. La PK es compuesta `(proyecto_id, id)`.

## 2. Capa de Negocio (`negocio/NVersion.php`)
Orquesta la subida física del archivo y dispara el flujo de notificaciones.

* **Método `subir($nombre, $peso_bytes, $proyecto_id, $ruta_archivo, $usuario_id, $estado)`**:
    1. Instancia `DVersion` para obtener el siguiente número de versión.
    2. Ejecuta `DVersion->subir(...)`.
    3. Llama internamente a `procesarNotificaciones($proyecto_id)`.
* **Método `procesarNotificaciones($proyecto_id)`**:
    1. Instancia `NAsignacion` para obtener el listado de docentes (Tribunal/Tutor) asignados a ese proyecto.
    2. Por cada docente, instancia `NNotificacion` y crea una alerta: "Nueva versión subida en el proyecto #$proyecto_id".

## 3. Capa de Presentación (Bridge API - `api/versiones/subir.php`)
Receptor del formulario `multipart/form-data`.

* **Lógica**:
    1. Captura `proyecto_id` y `usuario_id` (de la sesión).
    2. Procesa `$_FILES['archivo']`.
    3. **Movimiento Físico**: Mueve el archivo a `/uploads/versiones/` siguiendo la nomenclatura del `AGENTS.md`: `{proyecto_id}_{numero}_{timestamp}.{ext}`.
    4. Llama a `NVersion->subir(...)` pasando la ruta relativa generada.
    5. Retorna JSON de éxito.

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/versiones/subir.php`)
* **Diseño**: **Tailwind CSS**.
* **Componentes**:
    * Selector de Proyecto (donde el usuario es "Estudiante").
    * Campo `input type="file"` (aceptar .pdf, .docx).
    * Botón "Subir Avance".
    * Tabla de historial de versiones del proyecto seleccionado (consumiendo `api/versiones/index.php`).

### JavaScript (`presentacion/assets/js/versiones.js`)
* **`subirArchivo()`**: Utiliza `FormData` para enviar el archivo físico y los metadatos al Bridge API.
* **`listarHistorial(proyecto_id)`**: Refresca la tabla de versiones cada vez que se selecciona un proyecto o se sube un archivo nuevo.

---

## Notas de Implementación (Contexto IDE)
- **Persistencia Railway**: Según el `AGENTS.md`, los archivos en `/uploads/` son persistentes. Asegúrate de que el servidor tenga permisos de escritura en esa carpeta.
- **Sin Validaciones**: Siguiendo tu instrucción, no se validará el tamaño ni el tipo de archivo (MIME) en la capa de Negocio; se asume que el usuario sube el formato correcto.
- **PK Compuesta**: En `DVersion`, recuerda que la identidad de la versión depende del `proyecto_id`.
- **Descargas**: Implementar `api/versiones/descargar.php` usando `header()` y `readfile()` para servir los archivos desde la carpeta protegida.