<?php
require_once __DIR__ . '/../../app/Models/Clientes.php';

class ClientesController {

    public function listar() {
        $model = new Clientes();
        echo json_encode($model->listar());
    }

    public function guardar() {
        $input = json_decode(file_get_contents("php://input"), true);

        if (!isset($input['nombre']) || empty(trim($input['nombre']))) {
            echo json_encode(["success" => false, "message" => "Nombre requerido"]);
            return;
        }

        $nombre = trim($input['nombre']);


        $model = new Clientes();
        $resultado = $model->insertarCliente($nombre);

        if ($resultado) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al guardar"]);
        }
    }
}
