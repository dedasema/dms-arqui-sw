<?php

class DatabaseHelper
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        // Priorizar variables de entorno de Railway, con fallback a variables locales
        $host = getenv('PGHOST') ?: getenv('DB_HOST');
        $port = getenv('PGPORT') ?: getenv('DB_PORT');
        $dbname = getenv('PGDATABASE') ?: getenv('DB_NAME');
        $user = getenv('PGUSER') ?: getenv('DB_USER');
        $password = getenv('PGPASSWORD') ?: getenv('DB_PASS');
        
        if (!$host || !$port || !$dbname || !$user) {
            throw new Exception("Error de configuración: Faltan credenciales de base de datos.");
        }

        // DSN con sslmode=require obligatorio para Railway/Neon
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        // $dsn = "pgsql:host={$host};port={$port};dbname={$dbname};sslmode=require";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Deja la preparación al motor DB
            PDO::ATTR_PERSISTENT         => false  // Evita agotar el pool
        ];

        try {
            $this->conn = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            // Seguridad real: bloqueamos la filtración del DSN o Auth en el log del error
            throw new Exception("Error crítico: No se pudo conectar a la base de datos.");
        }
    }

    // Previene clonación del Singleton
    private function __clone() {}

    // Previene deserialización del Singleton
    public function __wakeup()
    {
        throw new Exception("No se puede deserializar un Singleton.");
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->conn;
    }
}
