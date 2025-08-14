<?php
function requireLogin() {
    // Inicia la sesión solo si no existe
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user_id']) || empty($_SESSION['username'])) {

        // Limpiar sesión
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();

        // Evitar loop de redirecciones
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($currentPath !== '/fleet' && $currentPath !== '/fleet/login') {
            header('Location: /fleet');
            exit;
        }
    }
}
