<?php


class Database
{
    private static $conn = null;

    public static function connect()
    {
        if (self::$conn) return self::$conn;

        $cfg = require __DIR__ . '/../Config/config.php';
        $trust = $cfg['db']['trust'] ? 'yes' : 'no';
        $dsn = "sqlsrv:Server={$cfg['db']['host']},{$cfg['db']['port']};Database={$cfg['db']['dbname']};TrustServerCertificate=$trust";

        $password = $cfg['db']['pass'];
        if (is_object($password) && method_exists($password, '__toString')) {
            $password = (string)$password;
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            self::$conn = new PDO($dsn, $cfg['db']['user'], $password, $options);

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return self::$conn;
    }

}

