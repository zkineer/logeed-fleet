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

    public function insertarCliente($nombre) {
        try {
            $sql = "INSERT INTO e_clientes (Nombre) VALUES (:nombre)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error insertarCliente: " . $e->getMessage());
            return false;
        }
    }
}
