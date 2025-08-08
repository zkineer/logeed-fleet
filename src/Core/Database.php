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

        self::$conn = new PDO($dsn, $cfg['db']['user'], $cfg['db']['pass']);
        self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return self::$conn;
    }
}
