<?php

try {
    header('Content-Type: application/json');

    require_once __DIR__ . '/../controllers/UserController.php';

    $userController = new UserController();

    $method = $_SERVER['REQUEST_METHOD'];
    $action = $_GET['action'] ?? null;

    if (!$action) {
        throw new Exception("Falta el parámetro 'action'.");
    }

    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                echo json_encode($userController->listUsers());
            } elseif ($action === 'show' && isset($_GET['id'])) {
                echo json_encode($userController->showUser($_GET['id']));
            } elseif ($action === 'getUser' && isset($_GET['user_id']) && isset($_GET['token'])) {
                echo json_encode($userController->getUser($_GET['user_id'], $_GET['token']));
            } else {
                throw new Exception("Acción no válida en GET.");
            }
            break;

        case 'POST':
            if ($action === 'create') {
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($userController->createUser($data));
            } else {
                throw new Exception("Acción no válida en POST.");
            }
            break;

        case 'PUT':
            if ($action === 'edit' && isset($_GET['id'])) {
                $data = json_decode(file_get_contents("php://input"), true);
                echo json_encode($userController->editUser($_GET['id'], $data));
            } else {
                throw new Exception("Acción no válida en PUT.");
            }
            break;

        case 'DELETE':
            if ($action === 'delete' && isset($_GET['id'])) {
                echo json_encode($userController->removeUser($_GET['id']));
            } else {
                throw new Exception("Acción no válida en DELETE.");
            }
            break;

        default:
            throw new Exception("Método HTTP no permitido.");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno en el servidor', 'details' => $e->getMessage()]);
}
