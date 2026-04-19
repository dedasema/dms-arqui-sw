# Fase 7: Especificación Técnica - CU0 Gestionar Usuarios

## 1. Capa de Datos (`datos/DUsuario.php`)
Esta clase gestiona la persistencia de los usuarios en PostgreSQL.

* **Método `crearUsuario($nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)`**:
    * **SQL**: `INSERT INTO usuario (nombre_completo, correo, contrasena, rol, codigo, carrera_id) VALUES (?, ?, ?, ?, ?, ?) RETURNING id;`
    * **Acción**: Inserta el registro y retorna el ID generado.
* **Método `editarUsuario($id, $nombre, $correo, $contrasena, $rol, $codigo, $carrera_id)`**:
    * **SQL**: `UPDATE usuario SET nombre_completo = ?, correo = ?, contrasena = ?, rol = ?, codigo = ?, carrera_id = ? WHERE id = ?;`
    * **Acción**: Actualiza los datos del usuario según su ID.
* **Método `eliminarUsuario($id)`**:
    * **SQL (Soft Delete)**: `UPDATE usuario SET eliminado = TRUE WHERE id = ?;`
    * **Acción**: Marca al usuario como eliminado (borrado lógico).
* **Método `obtenerUsuarios()`**:
    * **SQL**: `SELECT u.*, c.nombre as nombre_carrera FROM usuario u JOIN carrera c ON u.carrera_id = c.id WHERE u.eliminado = FALSE ORDER BY u.id DESC;`
    * **Acción**: Retorna el listado de usuarios activos con el nombre de su carrera.

---

## 2. Capa de Negocio (`negocio/NUsuario.php`)
Orquestador de la lógica de usuarios. Cumple la regla de **Cero Validaciones**.

* **Método `crearUsuario(...)`**: Instancia `DUsuario` y ejecuta la creación. Se recibe la contraseña tal cual llega (según instrucción de asumir que todo está bien).
* **Método `editarUsuario(...)`**: Instancia `DUsuario` y ejecuta la actualización.
* **Método `eliminarUsuario($id)`**: Instancia `DUsuario` y ejecuta el borrado lógico.
* **Método `obtenerUsuarios()`**: Instancia `DUsuario` y retorna el listado.

---

## 3. Capa de Presentación (Bridge API - `api/usuarios/index.php`)
Punto de entrada para las peticiones desde el frontend.

* **GET**: Llama a `NUsuario->obtenerUsuarios()` y devuelve JSON.
* **POST**:
    * Captura: `id`, `nombre_completo`, `correo`, `contrasena`, `rol`, `codigo`, `carrera_id`.
    * Si `id > 0`: Ejecuta `editarUsuario`.
    * Si `id == 0`: Ejecuta `crearUsuario`.
* **Acción Eliminar**: Si se recibe flag de eliminación, ejecuta `eliminarUsuario`.

---

## 4. Capa de Presentación (Interfaz de Usuario)

### Vista (`presentacion/usuarios/index.php`)
* **Diseño**: **Tailwind CSS**.
* **Componentes**:
    * Tabla con columnas: **ID**, **Código**, **Nombre**, **Correo**, **Rol**, **Carrera** y **Acciones**.
    * **Modal de Formulario**:
        * Inputs para `nombre_completo`, `correo`, `contrasena`, `codigo` y `rol` (Select: Estudiante, Docente, Administrador).
        * **Select de Carrera**: Debe poblarse dinámicamente llamando a `NCarrera->obtenerCarreras()`.

### JavaScript (`presentacion/assets/js/usuarios.js`)
* **`listar()`**: Carga usuarios y llena la tabla.
* **`cargarCarreras()`**: Carga el listado de carreras para el select del formulario.
* **`guardar()`**: Envía los datos al Bridge API y refresca la vista.
* **`eliminar(id)`**: Procesa el borrado lógico tras confirmación.

---

## Notas de Implementación (Contexto IDE)
- **Atributo Código**: Se añade `codigo` a la lógica de inserción y actualización como `string` (ej. registro universitario o ID de empleado).
- **Integridad**: El `carrera_id` es obligatorio para que el `INSERT` no falle en PostgreSQL.
- **Railway**: Asegurarse de que la tabla `usuario` tenga las columnas correspondientes antes de ejecutar.