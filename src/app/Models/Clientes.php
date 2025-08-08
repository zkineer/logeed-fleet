<?php
class Clientes {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function listar() {
        return $this->db
            ->query("EXEC sp_clientes_listar")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
