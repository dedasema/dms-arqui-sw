# Fase 10: Especificación Técnica - CU7 Administrar Comentarios / Revisiones

## 1. Capa de Datos (`datos/DComentario.php`)
Gestiona la persistencia de las observaciones realizadas por los revisores en la tabla `comentario`.

* **Método `insertar($mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id)`**:
    * **SQL**: `INSERT INTO comentario (mensaje, ruta_archivo, version_id, usuario_id, proyecto_id) VALUES (?, ?, ?, ?, ?) RETURNING id;`
    * **Acción**: Registra el comentario. La PK es compuesta `(proyecto_id, version_id, id)`.
* **Relación con DVersion**: Como indica el diagrama, `DComentario` puede consultar `DVersion->obtenerEstado()` para verificar que la versión aún está bajo revisión antes de comentar.

## 2. Capa de Negocio (`negocio/NComentario.php`)
Orquesta el registro del feedback y la notificación de vuelta al estudiante.

* **Método `insertar($mensaje, $ruta_archivo, $version_id, $usuario_id, $proyecto_id)`**:
    1. Instancia `DComentario` y ejecuta la inserción.
    2. Si el registro es exitoso, llama a `procesarNotificaciones($proyecto_id)`.
* **Método `procesarNotificaciones($proyecto_id)`**:
    1. Instancia `NAsignacion` para ejecutar `obtenerEstudiantes($proyecto_id)`.
    2. Por cada estudiante vinculado al proyecto, instancia `NNotificacion` para crear una alerta: "Tienes una nueva revisión en tu proyecto #$proyecto_id".

## 3. Capa de Presentación (Bridge API - `api/comentarios/index.php`)
Punto de entrada para el feedback de los docentes.

* **Lógica**:
    1. Captura `mensaje`, `version_id`, `proyecto_id` y el `usuario_id` del docente (desde la sesión).
    2. **Manejo de Adjuntos**: Si el docente sube un archivo (ej. el PDF corregido), se mueve a `/uploads/comentarios/` con el nombre `{proyecto_id}_rev_{version_id}_{timestamp}.{ext}`.
    3. Llama a `NComentario->insertar(...)` con la ruta del archivo (o null si no hay adjunto).
    4. Retorna JSON de éxito.

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/comentarios/index.php`)
* **Diseño**: **Tailwind CSS**.
* **Componentes**:
    * Visualizador de la versión seleccionada (datos del archivo original).
    * Área de texto (`textarea`) para el mensaje de revisión.
    * Input de archivo opcional para adjuntar correcciones.
    * Botón "Registrar Revisión".
    * Listado de comentarios previos (línea de tiempo de revisiones).

### JavaScript (`presentacion/assets/js/comentarios.js`)
* **`enviarComentario()`**: Captura los datos del formulario y utiliza `FormData` para procesar el envío al Bridge API (incluyendo el archivo opcional).
* **`listarComentarios(version_id)`**: Carga el historial de feedback de una versión específica.

---

## Notas de Implementación (Contexto IDE)
- **PK Compuesta**: Respeta la jerarquía de la base de datos: un comentario pertenece a una versión, que a su vez pertenece a un proyecto.
- **Flujo de Notificación**: A diferencia del CU6 (donde se notifica al docente), aquí el flujo es inverso: el docente comenta y se notifica al estudiante.
- **Railway/Filesystem**: Asegúrate de que la carpeta `/uploads/comentarios/` esté creada y tenga permisos de escritura.
- **Cierre de Ciclo**: Con este CU, el estado del proyecto podría cambiar a "Observado" o "Aprobado" (esto puede ser una lógica adicional en `NComentario` que llame a `NProyecto->actualizarEstado()`).