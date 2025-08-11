<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../app/Models/Cotizaciones.php';
require_once __DIR__ . '/../../app/Models/Clientes.php';
require_once __DIR__ . '/../../app/Models/Unidades.php';

class CotizacionesController {
    private $model;
    private $clientesModel;
    private $unidadesModel;
    public function __construct() {
        $this->model = new Cotizaciones();
        $this->clientesModel = new Clientes();
        $this->unidadesModel = new Unidades();
    }

    public function index() {
        $clientes = $this->clientesModel->listar();
        $unidades = $this->unidadesModel->listar();

        ob_start();
        include __DIR__ . '/../../app/Views/Cotizaciones/form.php';
        return ob_get_clean();
    }


    public function obtenerRangos() {
        header('Content-Type: application/json');

        if (empty($_GET['idUnidad'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Falta idUnidad']);
            exit;
        }

        try {
            $idUnidad = (int) $_GET['idUnidad'];
            $rangos = $this->unidadesModel->listarRangos($idUnidad);
            echo json_encode($rangos);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'server', 'msg' => $e->getMessage()]);
        }
        exit;
    }

    public function store() {
        header('Content-Type: application/json');

        if (
            empty($_POST['IdCliente']) || empty($_POST['idUnidad']) ||
            empty($_POST['idRango']) || empty($_POST['Kilometros']) ||
            empty($_POST['Costo']) || empty($_POST['Total'])
        ) {
            echo json_encode(["type" => "error", "message" => "Todos los campos son obligatorios"]);
            return;
        }

        $id = $this->model->insertar($_POST);

        if ($id) {
            echo json_encode(["type" => "success", "message" => "Cotización creada con ID: {$id}"]);
        } else {
            echo json_encode(["type" => "error", "message" => "Error al crear la cotización"]);
        }
    }


    public function buscar() {
        header('Content-Type: application/json');
        $term = $_GET['term'] ?? '';
        echo json_encode($this->model->buscar($term));
    }

    public function detalle($id) {
        header('Content-Type: application/json');
        echo json_encode($this->model->detalle($id));
    }


}
