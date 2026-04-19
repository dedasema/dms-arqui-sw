# Fase 9: Especificación Técnica - CU5 Gestionar Asignaciones

## 1. Capa de Datos (`datos/DDetalleAsignacion.php`)
Esta clase gestiona la tabla relacional entre usuarios y proyectos (la asignación de roles).

* **Método `crearAsignacion($usuario_id, $rol, $proyecto_id)`**:
    * **SQL**: `INSERT INTO asignacion_proyecto (usuario_id, rol, proyecto_id) VALUES (?, ?, ?);`
    * **Acción**: Inserta un nuevo registro de asignación.
* **Método `eliminarAsignacion($proyecto_id)`**:
    * **SQL (Soft Delete)**: `UPDATE asignacion_proyecto SET eliminado = TRUE WHERE proyecto_id = ?;`
    * **Acción**: Marca como eliminadas todas las asignaciones de un proyecto específico (según el diagrama).

## 2. Capa de Negocio (`negocio/NProyecto.php` - Extensión)
Se añaden métodos a la clase `NProyecto` para manejar la lógica de las asignaciones.

* **Método `crearAsignacion($proyecto_id, $usuarios)`**:
    * **Lógica**: Recibe el ID del proyecto y un array de usuarios con sus respectivos roles. Instancia `DDetalleAsignacion` y recorre el array para insertar cada relación.
* **Método `eliminarAsignacion($proyecto_id)`**:
    * **Lógica**: Instancia `DDetalleAsignacion` y ejecuta la eliminación lógica por proyecto.
* **Método `actualizarEstado($proyecto_id, $estado)`**:
    * **Lógica**: Instancia `DProyecto` para actualizar el estado del proyecto (ej. de "Iniciado" a "Asignado").

## 3. Capa de Presentación (Bridge API - `api/asignaciones/index.php`)
Punto de entrada para las peticiones de asignación.

* **GET**: Puede retornar el listado de asignaciones actuales filtradas por proyecto.
* **POST**:
    * Captura: `proyecto_id` y el array `usuarios` (objetos con `usuario_id` y `rol`).
    * Acción: Llama a `NProyecto->crearAsignacion()`.
* **DELETE / POST (flag)**: Llama a `NProyecto->eliminarAsignacion()`.

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/asignaciones/index.php`)
* **Diseño**: **Tailwind CSS**.
* **Componentes**:
    * Selector de **Proyecto** (poblado dinámicamente).
    * Lista de **Usuarios** disponibles con un selector de **Rol** (Tutor, Revisor, Tribunal, Estudiante).
    * Botón "Guardar Asignaciones".
    * Tabla de asignaciones actuales para ver quién pertenece a qué proyecto.

### JavaScript (`presentacion/assets/js/asignaciones.js`)
* **`obtenerProyectos()`**: Fetch a `api/proyectos/` para llenar el selector.
* **`obtenerUsuarios()`**: Fetch a `api/usuarios/` para mostrar la lista de personas asignables.
* **`crearAsignacion()`**: Recolecta el ID del proyecto y los usuarios seleccionados (con sus roles) y los envía al bridge API.

---

## Notas de Implementación (Contexto IDE)
- **PK Compuesta**: Recuerda que en la base de datos, la tabla de detalle suele usar una PK compuesta o el par `(proyecto_id, usuario_id)` debe ser único para evitar duplicados.
- **Flujo**: El diagrama muestra que `PAsignacion` depende de `NProyecto` y `NUsuario`. Asegúrate de que los archivos JS consuman correctamente ambos servicios.
- **Railway**: La tabla `asignacion_proyecto` debe reflejar la estructura de atributos `rol`, `fecha` y `eliminado`.