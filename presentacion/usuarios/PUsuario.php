<?php
/**
 * CLASE DE PRESENTACIÓN (BOUNDARY)
 * Transforma las solicitudes web de la vista y las envía a la capa de Negocio sincrónicamente.
 */
class PUsuario {
    private $nUsuario;

    public function __construct() {
        $this->nUsuario = new NUsuario();
    }

    public function obtenerUsuarios() {
        return $this->nUsuario->obtenerUsuarios();
    }

    public function crearUsuario($input) {
        $contrasena = password_hash($input['contrasena'], PASSWORD_BCRYPT);
        return $this->nUsuario->crearUsuario(
            $input['nombre_completo'], 
            $input['correo'], 
            $contrasena, 
            $input['rol'], 
            $input['codigo'] ?? null, 
            !empty($input['carrera_id']) ? $input['carrera_id'] : null
        );
    }

    public function editarUsuario($input) {
        $id = $input['id'];
        if (empty($input['contrasena'])) {
            $contrasena = $this->nUsuario->obtenerContrasenaActual($id);
        } else {
            $contrasena = password_hash($input['contrasena'], PASSWORD_BCRYPT);
        }
        
        return $this->nUsuario->editarUsuario(
            $id, 
            $input['nombre_completo'], 
            $input['correo'], 
            $contrasena, 
            $input['rol'], 
            $input['codigo'] ?? null, 
            !empty($input['carrera_id']) ? $input['carrera_id'] : null
        );
    }

    public function eliminarUsuario($id) {
        return $this->nUsuario->eliminarUsuario($id);
    }
}
