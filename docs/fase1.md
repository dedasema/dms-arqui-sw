# Especificación: Fase 1 - Setup del Proyecto y Base de Datos (PostgreSQL)

## Rol de la IA
Actúa como un Arquitecto de Software Senior experto en PHP nativo y PostgreSQL. A partir de este momento, utilizaremos Spec-Driven Development. No asumas estructuras, guíate estrictamente por mis especificaciones.

## Tareas a ejecutar
1. **Estructura de carpetas:** Crea una carpeta en la raíz del proyecto llamada `database`.
2. **Script SQL:** Dentro de `database`, crea un archivo llamado `schema.sql` e inserta EXACTAMENTE el script SQL que se encuentra al final de este documento (en la sección "Contexto SQL"). Este script será tu "Fuente de Verdad" para entender los tipos de datos y las llaves compuestas.
3. **Variables de entorno:** Crea un archivo `.env` en la raíz con las credenciales por defecto para PostgreSQL (DB_HOST=localhost, DB_PORT=5432, DB_NAME=titulacion_db, DB_USER=postgres, DB_PASS=root).
4. **Seguridad:** Crea un archivo `.gitignore` y añade `.env` para que no se suba al repositorio.

---
## Contexto SQL (Pegar tal cual en schema.sql)

-- =============================================================
-- DISEÑO FÍSICO - PostgreSQL
-- Sistema de Gestión de Titulación
-- Basado en diagrama conceptual con relaciones de composición
-- =============================================================

-- 1. CARRERA
CREATE TABLE carrera (
    id        SERIAL      PRIMARY KEY,
    nombre    VARCHAR(150) NOT NULL,
    sigla     VARCHAR(20)  NOT NULL,
    eliminado BOOLEAN      NOT NULL DEFAULT FALSE
);

-- 2. GESTION
CREATE TABLE gestion (
    id           SERIAL      PRIMARY KEY,
    codigo       VARCHAR(50)  NOT NULL,
    fecha_inicio DATE         NOT NULL,
    fecha_fin    DATE,
    eliminado    BOOLEAN      NOT NULL DEFAULT FALSE
);

-- 3. MODALIDAD_TITULACION
CREATE TABLE modalidad_titulacion (
    id          SERIAL       PRIMARY KEY,
    nombre      VARCHAR(150) NOT NULL,
    descripcion TEXT,
    eliminado   BOOLEAN      NOT NULL DEFAULT FALSE
);

-- 4. USUARIO
CREATE TABLE usuario (
    id              SERIAL       PRIMARY KEY,
    nombre_completo VARCHAR(200) NOT NULL,
    correo          VARCHAR(150) NOT NULL UNIQUE,
    contrasena      VARCHAR(255) NOT NULL,
    rol             VARCHAR(50)  NOT NULL,
    codigo          VARCHAR(50),
    eliminado       BOOLEAN      NOT NULL DEFAULT FALSE,
    carrera_id      INTEGER      NULL
        REFERENCES carrera(id) ON DELETE RESTRICT
);

-- 5. PROYECTO
CREATE TABLE proyecto (
    id           SERIAL       PRIMARY KEY,
    titulo       VARCHAR(300) NOT NULL,
    estado       VARCHAR(50),
    eliminado    BOOLEAN      NOT NULL DEFAULT FALSE,
    carrera_id   INTEGER      NULL
        REFERENCES carrera(id)              ON DELETE RESTRICT,
    modalidad_id INTEGER      NOT NULL
        REFERENCES modalidad_titulacion(id) ON DELETE RESTRICT,
    gestion_id   INTEGER      NOT NULL
        REFERENCES gestion(id)              ON DELETE RESTRICT
);

-- ---------------------------------------------------------------
-- RELACIONES DE COMPOSICIÓN
-- El PK es compuesto: (FK_tabla_padre, id_propio)
-- La existencia del hijo depende del padre → ON DELETE CASCADE
-- ---------------------------------------------------------------

-- 6. NOTIFICACION — composición de USUARIO
CREATE TABLE notificacion (
    usuario_id INTEGER NOT NULL
        REFERENCES usuario(id) ON DELETE CASCADE,
    id         SERIAL  NOT NULL,
    mensaje    TEXT    NOT NULL,
    leido      BOOLEAN NOT NULL DEFAULT FALSE,
    fecha      TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    eliminado  BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (usuario_id, id)
);

-- 7. VERSION_DOCUMENTAL — composición de PROYECTO
CREATE TABLE version_documental (
    proyecto_id  INTEGER      NOT NULL
        REFERENCES proyecto(id) ON DELETE CASCADE,
    id           SERIAL       NOT NULL,
    numero       INTEGER      NOT NULL,
    ruta_archivo VARCHAR(500),
    nombre       VARCHAR(200),
    peso_bytes   BIGINT,
    estado       VARCHAR(50),
    fecha        TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    eliminado    BOOLEAN      NOT NULL DEFAULT FALSE,
    usuario_id   INTEGER      NOT NULL
        REFERENCES usuario(id) ON DELETE RESTRICT,
    PRIMARY KEY (proyecto_id, id)
);

-- 8. ASIGNACION_PROYECTO — composición de PROYECTO
CREATE TABLE asignacion_proyecto (
    proyecto_id INTEGER      NOT NULL
        REFERENCES proyecto(id) ON DELETE CASCADE,
    id          SERIAL       NOT NULL,
    rol         VARCHAR(50),
    fecha       TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    eliminado   BOOLEAN      NOT NULL DEFAULT FALSE,
    usuario_id  INTEGER      NOT NULL
        REFERENCES usuario(id) ON DELETE RESTRICT,
    PRIMARY KEY (proyecto_id, id)
);

-- 9. COMENTARIO — composición de VERSION_DOCUMENTAL
CREATE TABLE comentario (
    proyecto_id  INTEGER NOT NULL,
    version_id   INTEGER NOT NULL,
    id           SERIAL  NOT NULL,
    mensaje      TEXT,
    ruta_archivo VARCHAR(500),
    fecha        TIMESTAMP WITH TIME ZONE NOT NULL DEFAULT NOW(),
    eliminado    BOOLEAN NOT NULL DEFAULT FALSE,
    usuario_id   INTEGER NOT NULL
        REFERENCES usuario(id) ON DELETE RESTRICT,
    PRIMARY KEY (proyecto_id, version_id, id),
    FOREIGN KEY (proyecto_id, version_id)
        REFERENCES version_documental(proyecto_id, id) ON DELETE CASCADE
);

-- =============================================================
-- ÍNDICES RECOMENDADOS
-- =============================================================
CREATE INDEX idx_usuario_correo         ON usuario(correo);
CREATE INDEX idx_notif_usuario_leido    ON notificacion(usuario_id, leido);
CREATE INDEX idx_proyecto_carrera       ON proyecto(carrera_id);
CREATE INDEX idx_proyecto_gestion       ON proyecto(gestion_id);
CREATE INDEX idx_proyecto_modalidad     ON proyecto(modalidad_id);
CREATE INDEX idx_version_proyecto       ON version_documental(proyecto_id);
CREATE INDEX idx_version_usuario        ON version_documental(usuario_id);
CREATE INDEX idx_asignacion_usuario     ON asignacion_proyecto(usuario_id);
CREATE INDEX idx_comentario_version     ON comentario(proyecto_id, version_id);
CREATE INDEX idx_comentario_usuario     ON comentario(usuario_id);