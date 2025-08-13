<?php
require __DIR__ . '/../Core/Router.php';
require __DIR__ . '/../app/Controllers/Cotizaciones.php';
require __DIR__ . '/../app/Controllers/Clientes.php';
require __DIR__ . '/../app/Controllers/Unidades.php';
require __DIR__ . '/../app/Controllers/Auth.php';



$router = new Router();
$cotizacionesController = new CotizacionesController();
$clientesController     = new ClientesController();
$unidadesController     = new UnidadesController();
$authController = new AuthController();

$router->get('/cotizaciones', [$cotizacionesController, 'index']);
$router->post('/cotizaciones', [$cotizacionesController, 'store']);
$router->get('/cotizaciones/obtenerRangos', [$cotizacionesController, 'obtenerRangos']);
$router->get('/cotizaciones/buscar', [$cotizacionesController, 'buscar']);
$router->get('/cotizaciones/detalle/{id}', [$cotizacionesController, 'detalle']);

$router->post('/clientes/guardar', [$clientesController, 'guardar']);
$router->get('/clientes/listar', [$clientesController, 'listar']);

$router->post('/unidades/guardar', [$unidadesController, 'guardar']);
$router->get('/unidades/listar', [$unidadesController, 'listar']);

$router->post('/unidades/guardarRango', [$unidadesController, 'guardarRango']);

$router->get('/', [$authController, 'index']);
$router->post('/login', [$authController, 'login']);


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');


if (strpos($uri, '/fleet') === 0) {
    $uri = substr($uri, strlen('/fleet'));
    if ($uri === '') {
        $uri = '/';
    }
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
