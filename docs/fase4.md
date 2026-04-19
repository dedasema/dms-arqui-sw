# Especificación: Fase 3 - CU1 Gestionar Carreras

## Objetivo
Implementar el CRUD completo de Carreras (Crear, Editar, Eliminar, Listar) siguiendo el diagrama UML y la estructura de carpetas de Railway.

## 1. Capa de Datos (`datos/DCarrera.php`)
Implementar los 4 métodos del UML usando SQL preparado:
- `crearCarrera($nombre, $sigla)`: `INSERT INTO carrera (nombre, sigla) VALUES (?, ?) RETURNING id`
- `editarCarrera($id, $nombre, $sigla)`: `UPDATE carrera SET nombre = ?, sigla = ? WHERE id = ?`
- `eliminarCarrera($id)`: `UPDATE carrera SET eliminado = TRUE WHERE id = ?`
- `obtenerCarreras()`: `SELECT id, nombre, sigla FROM carrera WHERE eliminado = FALSE ORDER BY id DESC`

## 2. Capa de Negocio (`negocio/NCarrera.php`)
- Debe instanciar `DCarrera`.
- Los métodos simplemente pasan los parámetros a la capa D sin validaciones (Regla AGENTS.md).
- Métodos: `crearCarrera`, `editarCarrera`, `eliminarCarrera`, `obtenerCarreras`.

## 3. Capa de Presentación (El Puente API)
Crea `api/carreras/index.php` para manejar las peticiones HTTP:
- **GET:** Llama a `NCarrera->obtenerCarreras()` y devuelve JSON.
- **POST:** Llama a `NCarrera->crearCarrera($nombre, $sigla)` y devuelve JSON.
- **PUT (o POST con flag):** Llama a `NCarrera->editarCarrera($id, $nombre, $sigla)`.
- **DELETE (o POST con flag):** Llama a `NCarrera->eliminarCarrera($id)`.

## 4. Capa de Presentación (Interfaz)
Crea los archivos en `presentacion/carreras/`:
- **`index.php`:** Contenedor HTML con Tailwind CDN. Debe incluir un modal para el formulario (Nombre, Sigla) y una tabla.
- **`../../assets/js/carreras.js`:** - Función `listar()`: Fetch GET a `/api/carreras/` y renderizar filas.
    - Función `guardar()`: Fetch POST a `/api/carreras/` (detectar si es nuevo o edición).
    - Función `eliminar(id)`: Fetch DELETE/POST a `/api/carreras/`.

## 5. Base de Datos (Configuración)
Asegúrate de que `config/DatabaseHelper.php` esté listo para leer:
`PGHOST, PGPORT, PGDATABASE, PGUSER, PGPASSWORD`.