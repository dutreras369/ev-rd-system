<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/RecordController.php';

// Inicializar controlador
$recordController = new RecordController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list' && isset($_GET['user_id'])) {
                echo json_encode($recordController->listRecords($_GET['user_id']));
            } else {
                throw new Exception('Acción no válida para GET', 400);
            }
            break;

        case 'POST':
            if ($action === 'register') {
                $data = json_decode(file_get_contents("php://input"), true);

                if (!isset($data['user_id'], $data['codigo_usuario'], $data['token'], $data['movement_type'], $data['amount'], $data['timestamp'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->createRecord($data);
                echo json_encode($response);
            } else {
                throw new Exception('Acción no válida para POST', 400);
            }
            break;

        default:
            throw new Exception('Método HTTP no permitido', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
