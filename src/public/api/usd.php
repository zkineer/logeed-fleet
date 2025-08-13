<?php
header('Content-Type: application/json');

$json = file_get_contents('https://api.exchangerate.host/latest?base=USD&symbols=MXN');
if ($json === false) {
    echo json_encode(['rate' => null]);
    exit;
}

$data = json_decode($json, true);
echo json_encode(['rate' => $data['rates']['MXN'] ?? null]);
