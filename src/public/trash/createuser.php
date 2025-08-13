<?php
// Datos de conexión
$host     = 'sqlserver';
$port     = '1433';
$database = 'master';
$username = 'sa';
$password = 'QJMuwa301100';
$trust    = true; // TrustServerCertificate

// Datos del usuario a insertar
$name           = "Angel Lopez";
$plainPassword  = "QJMuwa123";
$idEmpresa      = null;

// Colores para consola
$green = "\033[32m";
$red   = "\033[31m";
$reset = "\033[0m";

try {
    // Conexión a SQL Server con PDO
    $dsn = "sqlsrv:Server=$host,$port;Database=$database";
    if ($trust) {
        $dsn .= ";TrustServerCertificate=true";
    }

    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Encriptar la contraseña
    $hash = password_hash($plainPassword, PASSWORD_BCRYPT);

    // Ejecutar el procedimiento almacenado
    $stmt = $conn->prepare("EXEC sp_insertar_access_user @login_pswd = ?, @name = ?, @idEmpresa = ?");
    $stmt->bindParam(1, $hash);
    $stmt->bindParam(2, $name);
    $stmt->bindParam(3, $idEmpresa, PDO::PARAM_INT);
    $stmt->execute();

    // Mensaje en consola
    echo "{$green}[OK] Usuario insertado correctamente: {$name}{$reset}" . PHP_EOL;

} catch (PDOException $e) {
    echo "{$red}[ERROR] {$e->getMessage()}{$reset}" . PHP_EOL;
}
