<?php
class UnidadesController {

    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Leer el cuerpo según si viene en JSON o en form-data
            $input = json_decode(file_get_contents('php://input'), true);

            // Como el modal es genérico, tomamos el valor del campo que venga
            $nombreUnidad = trim($input['nombreUnidad'] ?? $input['nombre'] ?? $input['modalInput'] ?? '');

            if (empty($nombreUnidad)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'El nombre de la unidad es obligatorio']);
                return;
            }

            $unidad = new Unidades();
            $resultado = $unidad->guardar($nombreUnidad);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Unidad guardada correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'No se pudo guardar la unidad']);
            }
        }
    }

    public function guardarRango() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            $idUnidad = (int)($input['idUnidad'] ?? 0);
            $nombreRango = trim($input['nombreRango'] ?? $input['modalInput'] ?? '');
            $costo = trim($input['costo'] ?? '');

            if ($idUnidad <= 0 || empty($nombreRango) || $costo === '') {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
                return;
            }

            $unidad = new Unidades();
            $resultado = $unidad->guardarRango($idUnidad, $nombreRango, $costo);

            if ($resultado) {
                echo json_encode(['success' => true, 'message' => 'Rango guardado correctamente']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'No se pudo guardar el rango']);
            }
        }
    }



    public function listar() {
        $unidad = new Unidades();
        $unidades = $unidad->listar();
        echo json_encode($unidades);
    }
}
