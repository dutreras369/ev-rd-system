<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/RecordController.php';

// Obtener conexión con la base de datos
$recordController = new RecordController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'list' && isset($_GET['user_id'])) {
            echo json_encode($recordController->listRecords($_GET['user_id']));
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;

        case 'register':
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
                exit;
            }
        
            $inputData = json_decode(file_get_contents("php://input"), true);
            
            if (!$inputData) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
                exit;
            }
        
            $response = $recordController->createRecord($inputData);
            echo json_encode($response);
            break;        
        

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido']);
}
