<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'session.php';

// Redirección base obligatoria (Feature de la Fase 11)
if (isset($_SESSION['usuario_id'])) {
    header('Location: /presentacion/dashboard/');
} else {
    header('Location: /presentacion/login/');
}
exit;
