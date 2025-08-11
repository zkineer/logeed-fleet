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

        // Buscar ruta exacta
        if (isset($this->routes[$method][$path])) {
            echo call_user_func($this->routes[$method][$path]);
            return;
        }

        // Buscar con parámetros tipo {id}
        foreach ($this->routes[$method] as $route => $callback) {
            $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $route);
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                array_shift($matches);
                echo call_user_func_array($callback, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }
}
