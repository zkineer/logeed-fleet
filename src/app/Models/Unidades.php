<?php
class Unidades  {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function listar() {
        return $this->db
            ->query("EXEC sp_unidades_listar")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarRangos($idUnidad) {
        $stmt = $this->db->prepare("EXEC stp_rangos_por_unidad @idUnidad = :idUnidad");
        $stmt->bindValue(':idUnidad', $idUnidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardar($nombreUnidad) {
        $stmt = $this->db->prepare("EXEC sp_unidades_guardar @nombreUnidad = :nombreUnidad");
        $stmt->bindValue(':nombreUnidad', $nombreUnidad, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function guardarRango($idUnidad, $nombreRango, $costo) {
        $stmt = $this->db->prepare("EXEC sp_rangos_guardar 
        @idUnidad = :idUnidad, 
        @nombreRango = :nombreRango, 
        @costo = :costo");

        $stmt->bindValue(':idUnidad', $idUnidad, PDO::PARAM_INT);
        $stmt->bindValue(':nombreRango', $nombreRango, PDO::PARAM_STR);
        $stmt->bindValue(':costo', $costo, PDO::PARAM_STR);

        return $stmt->execute();
    }

}
