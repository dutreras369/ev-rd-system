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
            if ($action === 'list' && isset($_GET['user_id']) && isset($_GET['token'])) {

                if (!isset($_GET['user_id'], $_GET['page'], $_GET['limit'])) {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'error' => 'Faltan parámetros']);
                    exit;
                }
                
                $userId = $_GET['user_id'];
                $page = (int) $_GET['page'];
                $limit = (int) $_GET['limit'];
                $offset = ($page - 1) * $limit;

                // Obtener la fecha actual en formato YYYY-MM-DD
                $fecha = date('Y-m-d');

                // Obtener registros paginados
                $records = $recordController->listRecords($userId, $fecha, $limit, $offset);

                // Obtener el total de registros
                $totalRecords = $recordController->getTotalRecords($userId, $fecha);

                echo json_encode([
                    'success' => true,
                    'records' => $records,
                    'totalRecords' => $totalRecords,
                    'currentPage' => $page,
                    'totalPages' => ceil($totalRecords / $limit)
                ]);
                break;
            } else {
                throw new Exception('Acción no válida para GET', 400);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if ($action === 'register') {
                if (!isset($data['user_id'], $data['codigo_usuario'], $data['token'], $data['movement_type'], $data['amount'], $data['timestamp'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->createRecord($data);
                echo json_encode($response);
            } else if ($action === 'list' && isset($data['user_id'], $data['token'], $data['page'], $data['limit'])) {
                echo json_encode($recordController->listRecords($data['user_id'], $data['token'], $data['page'], $data['limit']));
            }             
            else {
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
