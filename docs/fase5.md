# Fase 6: Especificación Técnica - CU2 Gestionar Modalidades de Titulación

## 1. Capa de Datos (`datos/DModalidad.php`)
Esta clase gestiona la persistencia en la tabla `modalidad_titulacion` de PostgreSQL.

* **Método `crearModalidad($nombre, $descripcion)`**:
    * **SQL**: `INSERT INTO modalid_titulacion (nombre, descripcion) VALUES (?, ?) RETURNING id;`
    * **Acción**: Ejecuta la inserción y retorna el ID autogenerado.
* **Método `editarModalidad($id, $nombre, $descripcion)`**:
    * **SQL**: `UPDATE modalidad_titulacion SET nombre = ?, descripcion = ? WHERE id = ?;`
    * **Acción**: Actualiza los datos de la modalidad según su ID.
* **Método `eliminarModalidad($id)`**:
    * **SQL (Soft Delete)**: `UPDATE modalidad_titulacion SET eliminado = TRUE WHERE id = ?;`
    * **Acción**: Realiza el borrado lógico del registro.
* **Método `obtenerModalidades()`**:
    * **SQL**: `SELECT id, nombre, descripcion FROM modalidad_titulacion WHERE eliminado = FALSE ORDER BY id DESC;`
    * **Acción**: Retorna una lista con todas las modalidades activas.

---

## 2. Capa de Negocio (`negocio/NModalidad.php`)
Clase puente que conecta la presentación con la persistencia. Cumple con la regla de **Cero Validaciones**.

* **Método `crearModalidad($nombre, $descripcion)`**: Instancia `DModalidad` e invoca su método de creación.
* **Método `editarModalidad($id, $nombre, $descripcion)`**: Instancia `DModalidad` e invoca su método de edición.
* **Método `eliminarModalidad($id)`**: Instancia `DModalidad` e invoca su método de eliminación lógica.
* **Método `obtenerModalidades()`**: Instancia `DModalidad` y retorna el listado de registros.

---

## 3. Capa de Presentación (Bridge API - `api/modalidades/index.php`)
Punto de acceso para las peticiones `fetch`. Procesa los verbos HTTP.

* **GET**: Llama a `NModalidad->obtenerModalidades()` y devuelve el JSON.
* **POST**:
    * Si recibe `id` > 0: Ejecuta `editarModalidad`.
    * Si recibe `id` == 0 o nulo: Ejecuta `crearModalidad`.
* **DELETE / POST (con flag)**: Ejecuta `eliminarModalidad`.

---

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/modalidades/index.php`)
* **Diseño**: Implementado con **Tailwind CSS**.
* **Componentes**: 
    * Tabla interactiva para el listado.
    * Botón "Añadir" que despliega un modal.
    * Formulario dentro del modal con campos: `id` (hidden), `nombre` (input text) y `descripcion` (textarea).

### Lógica (`presentacion/assets/js/modalidades.js`)
* **`listar()`**: Realiza el fetch inicial para poblar la tabla.
* **`guardar()`**: Captura los datos del formulario modal y los envía al Bridge API. Recarga la tabla al finalizar.
* **`eliminar(id)`**: Envía el ID seleccionado al Bridge API para marcarlo como eliminado.

---

## Notas de Implementación (IDE Context)
- Asegurar que el `DatabaseHelper` esté configurado correctamente para Railway/Local.
- Seguir estrictamente los nombres de métodos definidos en el diagrama de clases adjunto.
- Recordar que el `id` en el formulario debe resetearse a 0 al abrir el modal para "Nueva Modalidad".