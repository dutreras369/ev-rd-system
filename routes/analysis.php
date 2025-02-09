<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/AnalysisController.php';

// Inicializar controlador
$analysisController = new AnalysisController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        /*case 'GET':
            if ($action === 'details') {
                if (!isset($_GET['user_id'], $_GET['token'], $_GET['record_id'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->getRecordDetails($_GET['record_id']);
                echo json_encode($response);
            } else {
                throw new Exception('Acción no válida para GET', 400);
            }
            break; */

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if ($action === 'total_records') {
                if (!isset($data['user_id'], $data['token'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $analysisController->getTotalStatusRecords();
                echo json_encode($response);

            }
        default:
            throw new Exception('Método HTTP no permitido', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
