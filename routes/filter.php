<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../controllers/FilterController.php';

$filterController = new FilterController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($action === 'filter_records') {
            if (!isset($data['token'], $data['offset'], $data['limit'])) {
                throw new Exception('Datos incompletos.', 400);
            }
            $response = $filterController->filterRecords($data);
            echo json_encode($response);
        } 
        elseif ($action === 'update_status') { // Nueva acción para actualizar estado
            if (!isset($data['record_id'], $data['status'], $data['token'])) {
                throw new Exception('Datos incompletos.', 400);
            }
            $response = $filterController->updateRecordStatus($data);
            echo json_encode($response);
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);

            if ($action === 'export_excel') {
                if (!isset($data['token'])) {
                    throw new Exception('Token no proporcionado.', 400);
                }
                $response = $filterController->exportExcel($data);
                echo json_encode($response);
            }
        } else {
            throw new Exception('Acción no válida para POST', 400);
        }
    }  
    else {
        throw new Exception('Método HTTP no permitido', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}


