# Fase 2: Especificación Técnica - DatabaseHelper (Railway Edition)

Este componente es el corazón de la persistencia del sistema. Está diseñado bajo el patrón **Singleton** para optimizar recursos en Railway y garantizar una conexión segura y única mediante **PDO**.

---

## 1. Identificación del Componente
* **Archivo:** `config/DatabaseHelper.php`
* **Namespace/Ubicación:** Carpeta raíz de configuración.
* **Patrón de Diseño:** Singleton.

---

## 2. Lógica de Conexión Híbrida (Local vs. Railway)
El Agente debe implementar una lógica que priorice las variables de entorno de Railway. Si no están presentes, debe recurrir a las variables locales cargadas por el `env_loader.php` (Fase 11).

### Variables de Entorno a Consultar (`getenv`):
1.  **Host:** `PGHOST` (Railway) o `DB_HOST` (Local).
2.  **Puerto:** `PGPORT` (Railway) o `DB_PORT` (Local).
3.  **Base de Datos:** `PGDATABASE` (Railway) o `DB_NAME` (Local).
4.  **Usuario:** `PGUSER` (Railway) o `DB_USER` (Local).
5.  **Contraseña:** `PGPASSWORD` (Railway) o `DB_PASS` (Local).

---

## 3. Requerimientos Técnicos (Rules for Code Gen)

* **DSN (Data Source Name):** `pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require`
  *Nota: El parámetro `sslmode=require` es obligatorio para conexiones externas seguras a bases de datos PostgreSQL en Railway.*

* **Configuración Obligatoria de PDO:**
    * `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`: Para que cualquier error dispare una excepción que pueda ser capturada.
    * `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC`: Para que los resultados sean siempre arrays asociativos.
    * `PDO::ATTR_EMULATE_PREPARES => false`: Para delegar la preparación de sentencias al motor de la base de datos (seguridad real).

---

## 4. Estructura de Métodos

### `public static function getInstance()`
* **Lógica:** Si la propiedad estática `$instance` es nula, crea una nueva instancia de la clase.
* **Retorno:** La instancia única de `DatabaseHelper`.

### `public function getConnection()`
* **Lógica:** Retorna el objeto `PDO` ya configurado.
* **Seguridad:** Si la conexión se pierde o falla, debe lanzar una excepción descriptiva que la Capa N pueda entender.

---

## 5. Notas de Seguridad para el SDD
1.  **Prevención de Inyección SQL:** El `DatabaseHelper` fuerza el uso de PDO, lo que obliga a las clases de la **Capa D** (Datos) a usar sentencias preparadas.
2.  **Gestión de Recursos:** Al ser un Singleton, evita la apertura masiva de sockets, lo cual es crítico en planes de infraestructura compartida como los de Railway.
3.  **Aislamiento de Credenciales:** Ninguna contraseña está escrita en el código; todas se inyectan en tiempo de ejecución.

---
**Instrucción para el Agente:** Generar el código PHP siguiendo estrictamente el estándar de nombres de variables de Railway.