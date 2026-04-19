<?php
require_once __DIR__ . '/../../config/env_loader.php';

session_start();
$_SESSION = [];
session_destroy();

header('Location: /presentacion/login/');
exit;
