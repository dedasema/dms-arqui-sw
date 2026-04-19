<?php
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $baseDir = dirname(__DIR__) . DIRECTORY_SEPARATOR;
    
    if (strpos($classPath, 'DatabaseHelper') !== false) {
        $file = $baseDir . 'config' . DIRECTORY_SEPARATOR . $classPath . '.php';
    } elseif (strpos($classPath, 'N') === 0) {
        $file = $baseDir . 'negocio' . DIRECTORY_SEPARATOR . $classPath . '.php';
    } elseif (strpos($classPath, 'D') === 0) {
        $file = $baseDir . 'datos' . DIRECTORY_SEPARATOR . $classPath . '.php';
    } else {
        return; 
    }
    
    if (file_exists($file)) {
        require_once $file;
    }
});
