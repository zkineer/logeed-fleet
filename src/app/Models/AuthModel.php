<?php

class AuthModel {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../../Core/Database.php';
        $this->db = Database::connect();
    }

    public function validarUsuario($username, $password) {
        $stmt = $this->db->prepare("EXEC sp_validar_login ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['login_pswd'])) {
            return $user; // Coinciden
        }

        return false; // No coincide
    }

}
