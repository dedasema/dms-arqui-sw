<?php
require_once __DIR__ . '/../../config/env_loader.php';
require_once __DIR__ . '/../../config/autoload.php';
require_once 'PAuth.php';

$pAuth = new PAuth();
$pAuth->logout();

header('Location: /presentacion/login/');
exit;
