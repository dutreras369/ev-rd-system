<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/UserController.php';

// Inicializar conexión y controlador
$pdo = Database::getConnection();
$userController = new UserController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if (!$action) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Falta el parámetro "action".']);
    exit;
}

switch ($action) {
    case 'list':
        if ($method !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
            exit;
        }
        echo json_encode($userController->listUsers());
        break;

    case 'show':
        if ($method !== 'GET' || !isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Parámetros inválidos o método no permitido.']);
            exit;
        }
        echo json_encode($userController->showUser($_GET['id']));
        break;

    case 'get_user': // Asegurar que se maneja POST correctamente
        if ($method !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
            exit;
        }

        // Obtener datos enviados en POST
        $data = json_decode(file_get_contents("php://input"), true);
        $userId = $data['user_id'] ?? null;
        $token = $data['token'] ?? null;

        if (!$userId || !$token) {
            echo json_encode(['success' => false, 'error' => 'Faltan datos (user_id o token).']);
            exit;
        }

        $response = $userController->getUser($userId, $token);
        echo json_encode($response);
        break;

    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        break;
}
