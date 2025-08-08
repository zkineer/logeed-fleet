<?php
require __DIR__ . '/../Core/Router.php';
require __DIR__ . '/../app/Controllers/Cotizaciones.php';

$router = new Router();

$router->get('/cotizaciones', [new CotizacionesController, 'index']);
$router->post('/cotizaciones', [new CotizacionesController, 'store']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
