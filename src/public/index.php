<?php
header('Content-Type: application/json; charset=utf-8');

$serverName = "sqlserver"; // nombre del contenedor
$database   = "master";    // cambia si tienes otra base
$username   = "sa";
$password   = "QJMuwa301100"; // tu clave real

try {
    $conn = new PDO(
        "sqlsrv:Server=$serverName,1433;Database=$database;Encrypt=yes;TrustServerCertificate=yes",
        $username,
        $password
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta de cotizaciones
    $stmt = $conn->query("SELECT TOP 10 * FROM dbo.e_cotizaciones ORDER BY idCotizacion DESC");
    $cotizaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'ok',
        'data'   => $cotizaciones
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}

