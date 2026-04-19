# Fase 6: Especificación Técnica - CU3 Gestionar Gestiones Académicas

## 1. Capa de Datos (`datos/DGestion.php`)
Esta clase se encarga de la persistencia de los periodos académicos en PostgreSQL.

* **Método `crearGestion($codigo, $fecha_inicio, $fecha_fin)`**:
    * **SQL**: `INSERT INTO gestion_academica (codigo, fecha_inicio, fecha_fin) VALUES (?, ?, ?) RETURNING id;`
    * **Acción**: Inserta una nueva gestión y retorna el ID generado.
* **Método `editarGestion($id, $codigo, $fecha_inicio, $fecha_fin)`**:
    * **SQL**: `UPDATE gestion_academica SET codigo = ?, fecha_inicio = ?, fecha_fin = ? WHERE id = ?;`
    * **Acción**: Actualiza los datos de la gestión académica.
* **Método `eliminarGestion($id)`**:
    * **SQL (Soft Delete)**: `UPDATE gestion_academica SET eliminado = TRUE WHERE id = ?;`
    * **Acción**: Realiza el borrado lógico del registro.
* **Método `obtenerGestiones()`**:
    * **SQL**: `SELECT id, codigo, fecha_inicio, fecha_fin FROM gestion_academica WHERE eliminado = FALSE ORDER BY fecha_inicio DESC;`
    * **Acción**: Retorna el listado de todas las gestiones activas.

---

## 2. Capa de Negocio (`negocio/NGestion.php`)
Clase orquestadora entre la API y la persistencia. Según el `AGENTS.md`, **no realiza validaciones** de fechas en esta etapa.

* **Método `crearGestion($codigo, $fecha_inicio, $fecha_fin)`**: Instancia `DGestion` y ejecuta la creación.
* **Método `editarGestion($id, $codigo, $fecha_inicio, $fecha_fin)`**: Instancia `DGestion` y ejecuta la edición.
* **Método `eliminarGestion($id)`**: Instancia `DGestion` y ejecuta la eliminación lógica.
* **Método `obtenerGestiones()`**: Instancia `DGestion` y retorna el array de datos.

---

## 3. Capa de Presentación (Bridge API - `api/gestiones/index.php`)
Punto de entrada para las peticiones asíncronas desde el frontend.

* **Lógica**:
    1. Cargar dependencias y `DatabaseHelper`.
    2. **GET**: Retornar JSON con `NGestion->obtenerGestiones()`.
    3. **POST**: Capturar `id`, `codigo`, `fecha_inicio`, `fecha_fin`.
        * Si `id > 0`: Ejecutar `editarGestion`.
        * Si `id == 0`: Ejecutar `crearGestion`.
    4. **Acción Eliminar**: Si se recibe un flag o método específico, ejecutar `eliminarGestion`.

---

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/gestiones/index.php`)
* **UI**:
    * Diseño con **Tailwind CSS**.
    * Botón "Nueva Gestión" para abrir el modal.
    * Tabla con columnas: **ID**, **Código**, **Fecha Inicio**, **Fecha Fin** y **Acciones**.
    * **Modal de Formulario**: 
        * `input type="hidden"` para el ID.
        * `input type="text"` para el código (ej. "1-2026").
        * `input type="date"` para fecha_inicio y fecha_fin.

### JavaScript (`presentacion/assets/js/gestiones.js`)
* **`listar()`**: Carga los datos desde la API y renderiza la tabla.
* **`guardar()`**: Recolecta los datos del formulario y los envía vía POST al bridge API. Refresca la tabla al terminar.
* **`eliminar(id)`**: Envía el ID para marcar la gestión como eliminada tras confirmación.

---

## Notas de Implementación (Contexto IDE)
- **Tipos de Datos**: Aunque en el UML `fecha_fin` figura como `int`, para la lógica de base de datos y formularios se tratará como `string` (date) para mantener consistencia con `fecha_inicio`, a menos que se refiera estrictamente al año.
- **Formato**: Asegurarse de que las fechas se guarden en formato ISO (`YYYY-MM-DD`) para compatibilidad total con PostgreSQL.
- **Railway**: La tabla `gestion_academica` debe existir según el script SQL inicial.