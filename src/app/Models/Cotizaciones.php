<?php
class Cotizaciones {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function listar() {
        return $this->db
            ->query("EXEC sp_cotizacion_listar")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertar($datos) {
        $sql = "EXEC sp_cotizacion_insertar :Serie, :Folio, :Fecha, :IdCliente";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':Serie' => $datos['Serie'],
            ':Folio' => $datos['Folio'],
            ':Fecha' => $datos['Fecha'],
            ':IdCliente' => $datos['IdCliente']
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['IdCotizacion'] ?? null;
    }
}
