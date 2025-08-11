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
        $sql = "EXEC sp_cotizacion_insertar 
        :IdCliente, :idUnidad, :idRango, :Kilometros, :Costo, :Total";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':IdCliente'  => $datos['IdCliente'],
            ':idUnidad'   => $datos['idUnidad'],
            ':idRango'    => $datos['idRango'],
            ':Kilometros' => $datos['Kilometros'],
            ':Costo'      => (float) preg_replace('/[^0-9.]/', '', $datos['Costo']),
            ':Total'      => (float) preg_replace('/[^0-9.]/', '', $datos['Total'])
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['IdCotizacion'] ?? null;
    }

    public function buscar($term) {
        $sql = "EXEC sp_cotizaciones_buscar :term";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function detalle($id) {
        $sql = "EXEC sp_cotizaciones_detalle :idCotizacion";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':idCotizacion' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
