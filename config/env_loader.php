<?php
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);

        // Ignorar líneas vacías o que comiencen por # (comentarios)
        if (empty($line) || strpos($line, '#') === 0) continue;

        // Separar nombre=valor (solo en el primer '=')
        $separatorPos = strpos($line, '=');
        if ($separatorPos === false) continue;

        $name  = trim(substr($line, 0, $separatorPos));
        $value = trim(substr($line, $separatorPos + 1));

        // Eliminar comillas envolventes simples o dobles del valor
        if (strlen($value) >= 2) {
            $first = $value[0];
            $last  = $value[strlen($value) - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name]    = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Cargar .env de la raíz si existe (Fallback para Entornos Locales)
loadEnv(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '.env');
