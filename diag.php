<?php
require_once __DIR__ . '/config/env_loader.php';

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$pdo  = new PDO("pgsql:host={$host};port={$port};dbname={$db}", $user, $pass);
$hash = password_hash('admin123', PASSWORD_BCRYPT);

$stmt = $pdo->prepare("UPDATE usuario SET contrasena = ? WHERE correo = ?");
$stmt->execute([$hash, 'admin@test.com']);

echo "Hash actualizado correctamente.\n";
echo "Hash: $hash\n";

// Verificar
$stmt2 = $pdo->prepare("SELECT contrasena FROM usuario WHERE correo = ?");
$stmt2->execute(['admin@test.com']);
$row = $stmt2->fetch(PDO::FETCH_ASSOC);
echo "password_verify: " . (password_verify('admin123', $row['contrasena']) ? 'OK ✅' : 'FALLO ❌') . "\n";
