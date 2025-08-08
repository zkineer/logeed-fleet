<?php
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../app/Models/Cotizaciones.php';
require_once __DIR__ . '/../../app/Models/Clientes.php';

class CotizacionesController {
    private $model;
    private $clientesModel;

    public function __construct() {
        $this->model = new Cotizaciones();
        $this->clientesModel = new Clientes();
    }

    public function index() {
        $clientes = $this->clientesModel->listar();
        ob_start();
        include __DIR__ . '/../../app/Views/Cotizaciones/form.php';
        return ob_get_clean();
    }

    public function store() {
        if (empty($_POST['IdCliente']) || empty($_POST['Serie']) || empty($_POST['Folio']) || empty($_POST['Fecha'])) {
            return "Todos los campos son obligatorios.";
        }

        $id = $this->model->insertar($_POST);
        return "Cotización creada con ID: " . $id;
    }
}
