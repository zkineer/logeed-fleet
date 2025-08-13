<?php
header('Content-Type: application/json');

// Obtener HTML desde nacionalgasolinero
$html = file_get_contents('https://www.nacionalgasolinero.com/');
if ($html === false) {
    echo json_encode(['price' => null]);
    exit;
}

// Buscar el precio del diésel para Aguascalientes
if (preg_match('/Aguascalientes.*?Di[eé]sel.*?\$([0-9]+\.[0-9]+)/si', $html, $m)) {
    echo json_encode(['price' => (float)$m[1]]);
} else {
    echo json_encode(['price' => null]);
}
