<?php
session_start();

function checkAccess($rolesPermitidos = []) {
    // Si no hay sesión activa, expulsar a login
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: /presentacion/login/');
        exit;
    }

    // Validar autorización de Rol si se proveyó una lista de roles permitidos
    if (!empty($rolesPermitidos) && !in_array($_SESSION['rol'], $rolesPermitidos)) {
        header('Location: /presentacion/dashboard/?error=forbidden');
        exit;
    }
}
