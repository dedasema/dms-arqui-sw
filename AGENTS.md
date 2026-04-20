# 📜 CONSTITUCIÓN DEL AGENTE: Sistema de Titulación (Railway Edition)

Este documento define las reglas inquebrantables de arquitectura, seguridad y desarrollo. El Agente DEBE consultar este archivo antes de realizar cualquier tarea para asegurar la integridad del Spec Driven Development (SDD).

---

## 1. Misión Arquitectónica (3 Capas Estrictas)
El sistema se rige por una arquitectura de **3 Capas Ortodoxas**. Está prohibido el uso de frameworks complejos o patrones MVC tradicionales.

* **Capa P (Presentación):** Clases Boundary `P[Entidad].php` y vistas PHP. Capturan la entrada del usuario (POST/GET), orquestan la llamada a la Capa N y renderizan la respuesta. No hay intermediarios REST ni AJAX.
* **Capa N (Negocio):** Clases `N[Entidad].php`. Contienen la lógica, orquestación y cambios de estado. No conocen la base de datos.
* **Capa D (Datos):** Clases `D[Entidad].php`. Única capa con permiso para ejecutar SQL mediante `DatabaseHelper`.

---

## 2. Estructura de Directorios
```text
titulacion-app/
├── config/             # DatabaseHelper, session, autoload, env_loader
├── datos/              # Clases D (Persistencia)
├── negocio/            # Clases N (Lógica)
├── presentacion/       # Clases P (Boundaries) y Vistas PHP nativas
└── uploads/            # Volumen persistente para archivos

## 3. Tienes terminantemente prohibido leer el archivo `.env` debes basarte en `.env.local`