<?php
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controllers/AnalysisController.php';

// Inicializar controlador
$analysisController = new AnalysisController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    if ($method === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($action === 'total_records') {
            if (!isset($data['user_id'], $data['token'])) {
                throw new Exception('Datos incompletos.', 400);
            }

            $response = $analysisController->getTotalStatusRecords();
            echo json_encode($response);
        } elseif ($action === 'user_records') {
            if (!isset($data['user_id'], $data['token'])) {
                throw new Exception('Datos incompletos.', 400);
            }

            $response = $analysisController->getUserRecords();
            echo json_encode($response);
        } else {
            throw new Exception('Acción no válida para POST', 400);
        }
    } else {
        throw new Exception('Método HTTP no permitido', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
