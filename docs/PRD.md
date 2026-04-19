# 📄 Product Requirements Document (PRD): Sistema "TesisFlow"

## 1. Información General
| Atributo | Detalle |
| :--- | :--- |
| **Nombre del Proyecto** | TesisFlow - Sistema de Gestión de Titulación |
| **Versión** | 1.0 |
| **Arquitectura** | 3 Capas Estrictas (Ortodoxa) |
| **Metodología** | Spec Driven Development (SDD) |
| **Stack Técnico** | PHP 8.5 (Nativo), PostgreSQL, Tailwind CSS, JS (Fetch API) |
| **Despliegue** | Railway (Nixpacks + Volúmenes Persistentes) |

---

## 2. Visión y Objetivos
**TesisFlow** es una plataforma diseñada para centralizar, automatizar y auditar el proceso de titulación universitaria. El sistema reemplaza el flujo físico y disperso por un ecosistema digital donde la interacción entre estudiantes y docentes es trazable y segura.

### Objetivos Principales:
1.  **Eliminar la burocracia:** Digitalizar la entrega y revisión de borradores de tesis.
2.  **Integridad Documental:** Mantener un historial de versiones inalterable.
3.  **Seguridad por Roles:** Garantizar que cada usuario solo acceda a las funciones que le corresponden.
4.  **Arquitectura Robusta:** Mantener una separación total entre la base de datos, la lógica de negocio y la interfaz de usuario.

---

## 3. Matriz de Roles y Permisos (RBAC)
El sistema implementa un Control de Acceso Basado en Roles (RBAC) definido en la Fase #11:

| Funcionalidad / Módulo | Administrador | Docente | Estudiante |
| :--- | :---: | :---: | :---: |
| Configuración de Catálogos (Carreras, Modalidades, Gestiones) | ✅ Total | 👁️ Leer | 👁️ Leer |
| Gestión de Usuarios (CU0) | ✅ Total | ❌ | ❌ |
| Registro de Proyectos (CU4) | ✅ Total | 👁️ Leer | ✅ Crear |
| Asignación de Tribunales/Tutores (CU5) | ✅ Total | ❌ | ❌ |
| Subida de Versiones Documentales (CU6) | 👁️ Leer | 👁️ Leer | ✅ Subir |
| Registro de Comentarios y Feedback (CU7) | 👁️ Leer | ✅ Crear | 👁️ Leer |

---

## 4. Requerimientos Funcionales

### Módulo de Administración (Catálogos Base)
* **CU1 (Carreras):** Gestión de nombres y siglas de las facultades/carreras.
* **CU2 (Modalidades):** Definición de tipos de titulación (Tesis, Proyecto de Grado, Examen).
* **CU3 (Gestiones):** Control de periodos académicos (Ej: 1-2024).

### Módulo de Gestión de Usuarios y Acceso
* **CU0 (Usuarios):** Registro de perfiles con atributos de código, carrera y rol.
* **Autenticación:** Sistema de Login basado en sesiones PHP nativas con encriptación BCRYPT.

### Módulo Operativo (Core)
* **CU4 (Proyectos):** Creación del perfil del proyecto con máquina de estados (Iniciado -> Aprobado).
* **CU5 (Asignaciones):** Vinculación de docentes con roles específicos (Tutor, Tribunal) a proyectos.
* **CU6 (Versiones):** Sistema de carga de archivos físicos (.pdf, .docx) con numeración automática.
* **CU7 (Comentarios):** Registro de revisiones y feedback por parte del tribunal asignado.

---

## 5. Especificaciones Técnicas y Arquitectura

### Arquitectura de 3 Capas
1.  **Capa de Presentación (P):** HTML5/Tailwind y archivos `api/*.php`. Los archivos de la API actúan como puente, recibiendo datos y devolviendo JSON estándar.
2.  **Capa de Negocio (N):** Clases que contienen la lógica pura, validaciones de estados y orquestación de servicios.
3.  **Capa de Datos (D):** Persistencia mediante PostgreSQL. Uso obligatorio del patrón Singleton en `DatabaseHelper`.

### Seguridad (Estrategia SDD)
* **Inyección SQL:** Bloqueada mediante el uso mandatorio de **Sentencias Preparadas (PDO)**.
* **Protección de Servidor:** Carpeta `/uploads/` bloqueada para ejecución de scripts PHP vía `.htaccess`.
* **Validación de Sesión:** Middleware centralizado (`session.php`) que valida la identidad en cada petición.

---

## 6. Metodología de Implementación: SDD
El proyecto se desarrolla bajo la metodología **Spec Driven Development**, donde la programación es el resultado final de una especificación técnica rigurosa:
* **Fase 1-2:** Persistencia y Conexión.
* **Fase 11:** Infraestructura y Pegamento (Autoload, Session, Layout).
* **Fases 3-10:** Implementación granular de Casos de Uso.

---

## 7. Infraestructura de Despliegue (Railway)
* **Motor de Base de Datos:** PostgreSQL.
* **Filesystem:** Uso de volúmenes persistentes para la carpeta `/uploads/`.
* **Configuración:** Manejo de secretos mediante variables de entorno (`getenv`).