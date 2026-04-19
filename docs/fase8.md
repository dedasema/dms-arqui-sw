# Fase 8: Especificación Técnica - CU4 Gestionar Proyectos

## 1. Capa de Datos (`datos/DProyecto.php`)
Esta clase gestiona la persistencia de los proyectos de titulación en PostgreSQL.

* **Método `crearProyecto($titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)`**:
    * **SQL**: `INSERT INTO proyecto (titulo, estado, carrera_id, modalidad_id, gestion_id) VALUES (?, ?, ?, ?, ?) RETURNING id;`
    * **Acción**: Inserta el proyecto y retorna el ID generado.
* **Método `editarProyecto($id, $titulo, $estado, $carrera_id, $modalidad_id, $gestion_id)`**:
    * **SQL**: `UPDATE proyecto SET titulo = ?, estado = ?, carrera_id = ?, modalidad_id = ?, gestion_id = ? WHERE id = ?;`
    * **Acción**: Actualiza los datos del proyecto según su ID.
* **Método `eliminarProyecto($id)`**:
    * **SQL (Soft Delete)**: `UPDATE proyecto SET eliminado = TRUE WHERE id = ?;`
    * **Acción**: Realiza el borrado lógico del registro.
* **Método `obtenerProyectos()`**:
    * **SQL**: 
      ```sql
      SELECT p.*, c.nombre as nombre_carrera, m.nombre as nombre_modalidad, g.codigo as codigo_gestion 
      FROM proyecto p
      JOIN carrera c ON p.carrera_id = c.id
      JOIN modalidad_titulacion m ON p.modalidad_id = m.id
      JOIN gestion_academica g ON p.gestion_id = g.id
      WHERE p.eliminado = FALSE ORDER BY p.id DESC;
      ```
    * **Acción**: Retorna el listado de proyectos con los nombres de sus relaciones para la vista.

---

## 2. Capa de Negocio (`negocio/NProyecto.php`)
Orquestador de la lógica de proyectos. 

* **Método `crearProyecto(...)`**: Instancia `DProyecto` y ejecuta la creación.
* **Método `editarProyecto(...)`**: Instancia `DProyecto` y ejecuta la actualización.
* **Método `eliminarProyecto($id)`**: Instancia `DProyecto` y ejecuta el borrado lógico.
* **Método `obtenerProyecto()`**: Instancia `DProyecto` y retorna el listado procesado.

---

## 3. Capa de Presentación (Bridge API - `api/proyectos/index.php`)
Punto de entrada para las peticiones desde el frontend.

* **GET**: Llama a `NProyecto->obtenerProyecto()` y devuelve JSON.
* **POST**:
    * Captura: `id`, `titulo`, `estado`, `carrera_id`, `modalidad_id`, `gestion_id`.
    * Si `id > 0`: Ejecuta `editarProyecto`.
    * Si `id == 0`: Ejecuta `crearProyecto`.
* **Acción Eliminar**: Si se recibe flag de eliminación, ejecuta `eliminarProyecto`.

---

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/proyectos/index.php`)
* **Diseño**: **Tailwind CSS**.
* **Componentes**:
    * Tabla con columnas: **ID**, **Título**, **Estado**, **Carrera**, **Modalidad**, **Gestión** y **Acciones**.
    * **Modal de Formulario**:
        * Input para `titulo` (string).
        * Select para `estado` (Iniciado, En Revisión, Aprobado, etc.).
        * **Selects dinámicos**: Para `carrera_id`, `modalidad_id` y `gestion_id`.

### JavaScript (`presentacion/assets/js/proyectos.js`)
* **`listar()`**: Carga proyectos y llena la tabla principal.
* **`cargarSelects()`**: 
    * Llama a `NCarrera->obtenerCarreras()` (vía su API).
    * Llama a `NModalidad->obtenerModalidades()` (vía su API).
    * Llama a `NGestion->obtenerGestiones()` (vía su API).
* **`guardar()`**: Envía los datos al Bridge API y refresca la tabla.
* **`eliminar(id)`**: Procesa el borrado lógico tras confirmación.

---

## Notas de Implementación (Contexto IDE)
- **Estados**: Se recomienda manejar un set predefinido de estados (ej. "PENDIENTE", "EN PROCESO", "FINALIZADO").
- **Integridad**: Al crear un proyecto, asegúrate de que los IDs de carrera, modalidad y gestión existan previamente en sus tablas correspondientes.
- **Railway**: La tabla `proyecto` debe tener las llaves foráneas correctamente configuradas hacia `carrera`, `modalidad_titulacion` y `gestion_academica`.