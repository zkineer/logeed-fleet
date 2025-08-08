<?php
class Router {
    private $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch($method, $uri) {
        $path = parse_url($uri, PHP_URL_PATH);

        // Detectar y quitar base path /fleet
        $basePath = '/fleet';
        if (strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        // Buscar la ruta registrada
        $callback = $this->routes[$method][$path] ?? null;

        if (!$callback) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        echo call_user_func($callback);
    }
}
