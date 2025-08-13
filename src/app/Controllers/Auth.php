<?php

class AuthController {
    public function index() {
        include __DIR__ . '/../Views/Auth/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            require_once __DIR__ . '/../../app/Models/AuthModel.php';
            $authModel = new AuthModel();

            $user = $authModel->validarUsuario($username, $password);

            if ($user) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['name'];

                header('Location: /fleet/cotizaciones');
                exit;
            } else {
                session_start();
                $_SESSION['error'] = 'Usuario o contraseña inválidos';
                header('Location: /fleet');
                exit;
            }
        }
    }

}
