# Fase 11: Infraestructura, Integración y Seguridad Global

Este documento establece la base técnica y el "pegamento" arquitectónico del sistema, asegurando que todas las fases anteriores funcionen como un ecosistema unificado y seguro.

---

## 1. El Guardián de Sesión y RBAC (`config/session.php`)
* **Lógica:** Inicia la sesión mediante `session_start()`.
* **Función `checkAccess($rolesPermitidos = [])`**: 
    * Verifica si `$_SESSION['usuario_id']` está definido. Si no, redirige a `/presentacion/login/index.html`.
    * Si se especifican roles, valida que `$_SESSION['rol']` sea uno de los permitidos.
    * Si el rol no tiene permiso, redirige al Dashboard con un código de error de acceso.

## 2. Script de Autocarga Dinámico (`config/autoload.php`)
* **Mecanismo:** Implementa `spl_autoload_register`.
* **Mapeo:** * Clases que empiezan con `N` se buscan en `/negocio/`.
    * Clases que empiezan con `D` se buscan en `/datos/`.
    * Clase `DatabaseHelper` se busca en `/config/`.
* **Compatibilidad:** Usa `DIRECTORY_SEPARATOR` para asegurar funcionamiento tanto en Local (Windows/Mac) como en Railway (Linux).

## 3. El Helper de API Centralizado (`presentacion/assets/js/api.js`)
* **Objetivo:** Estandarizar las peticiones `fetch`.
* **Funciones:** `get(url)`, `post(url, data)`, `put(url, data)`, `delete(url, data)`.
* **Seguridad:** Incluye un interceptor que detecta respuestas `401 Unauthorized` para forzar el cierre de sesión y redirección al login desde el lado del cliente.

## 4. Layout Maestro (`presentacion/componentes/layout.php`)
* **Estructura:** Archivo PHP que encapsula el `header` (Tailwind CDN, fuentes), el `navbar` y el `sidebar`.
* **Navegación Dinámica:** El menú del Sidebar se filtra según el valor de `$_SESSION['rol']`.
* **Implementación:** Las vistas de cada Caso de Uso deben hacer un `include` de este archivo para heredar el diseño global.

## 5. El Router de Entrada (`index.php` en raíz)
* **Lógica de Tráfico:** * Si existe sesión activa: `header('Location: /presentacion/dashboard/');`
    * Si no existe sesión: `header('Location: /presentacion/login/');`

## 6. Configuración de Entorno Local y Producción (`config/env_loader.php`)
* **Lógica:** Si no se detectan las variables de entorno de Railway (ej. `PGHOST`), el sistema intenta cargar un archivo `.env` local.
* **Propósito:** Garantizar que el código sea idéntico en desarrollo y producción.

---

# 📑 Consideraciones de Integración para el SDD (Software Design Document)

Esta sección documenta las decisiones de diseño de alto nivel que garantizan la integridad del sistema.

### A. Estrategia de Seguridad
1.  **Protección de Datos:** Uso obligatorio de **PDO Prepared Statements** en la Capa D para anular riesgos de Inyección SQL.
2.  **Seguridad de Archivos:** La carpeta `/uploads/` contiene un archivo `.htaccess` con `php_flag engine off` para evitar la ejecución de scripts maliciosos.
3.  **Criptografía:** Almacenamiento de credenciales mediante `password_hash()` con algoritmo **BCRYPT**.

### B. Matriz de Roles y Permisos (RBAC)
| Módulo / Caso de Uso | Estudiante | Docente | Administrador |
| :--- | :---: | :---: | :---: |
| CU1, CU2, CU3 (Configuración) | Leer | Leer | Total |
| CU0 (Usuarios) | ❌ | ❌ | Total |
| CU4 (Proyectos) | Crear/Leer | Leer | Total |
| CU5 (Asignaciones) | ❌ | ❌ | Total |
| CU6 (Versiones) | Subir | Leer | Leer |
| CU7 (Comentarios) | Leer | Crear | Leer |

### C. Máquina de Estados de Proyectos
El flujo de los proyectos se gestiona centralizadamente en la Capa de Negocio (`NProyecto`):
* **Iniciado:** Creado por el estudiante.
* **Asignado:** Administrador asigna tribunal/tutor.
* **En Revisión:** Estudiante sube versión documental.
* **Observado:** Docente registra comentarios con correcciones.
* **Aprobado:** Docente califica positivamente la última versión.

---
**Nota Final:** Este archivo Markdown debe ser procesado como la "Hoja de Ruta de Integración" antes de iniciar la programación de los Casos de Uso individuales.