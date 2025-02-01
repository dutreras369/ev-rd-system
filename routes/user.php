<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/UserController.php';

// Obtener conexión con la base de datos
$userController = new UserController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'list') {
            echo json_encode($userController->listUsers());
        } elseif ($action === 'show' && isset($_GET['id'])) {
            echo json_encode($userController->showUser($_GET['id']));
        }  elseif ($action === 'getUser' && isset($_GET['user_id']) && isset($_GET['token'])) {
            echo json_encode($userController->getUser($_GET['user_id'], $_GET['token']));
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;

    case 'POST':
        if ($action === 'create') {
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($userController->createUser($data));
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;

    case 'PUT':
        if ($action === 'edit' && isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"), true);
            echo json_encode($userController->editUser($_GET['id'], $data));
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;

    case 'DELETE':
        if ($action === 'delete' && isset($_GET['id'])) {
            echo json_encode($userController->removeUser($_GET['id']));
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido']);
}
