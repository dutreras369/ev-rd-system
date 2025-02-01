<?php

try {

    header('Content-Type: application/json');

    require_once __DIR__ . '/../controllers/UserController.php';

    // Obtener conexión con la base de datos
    $userController = new UserController();

    $response = ['success' => false, 'error' => 'Acción no válida.'];

    if (!isset($_GET['action'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Falta el parámetro "action".']);
        exit;
    }

    $action = $_GET['action'];
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($action) {
        case 'list':
            if ($method !== 'GET') {
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
                exit;
            }
            $response = $userController->listUsers();
            break;

        case 'get_user':
            if ($method !== 'POST') { // Preferimos POST para mayor seguridad con el token
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
                exit;
            }

            $userId = $_POST['user_id'] ?? null;
            $token = $_POST['token'] ?? null;

            if ($userId && $token) {
                $response = $userController->getUser($userId, $token);
            } else {
                $response = ['success' => false, 'error' => 'Faltan datos (user_id o token).'];
            }
            break;

        case 'show':
            if ($method !== 'GET' || !isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Acción no válida o falta ID.']);
                exit;
            }
            $response = $userController->showUser($_GET['id']);
            break;

        case 'create':
            if ($method !== 'POST') {
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
                exit;
            }
            $data = json_decode(file_get_contents("php://input"), true);
            if ($data) {
                $response = $userController->createUser($data);
            } else {
                $response = ['success' => false, 'error' => 'Datos inválidos.'];
            }
            break;

        case 'edit':
            if ($method !== 'PUT' || !isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Acción no válida o falta ID.']);
                exit;
            }
            $data = json_decode(file_get_contents("php://input"), true);
            if ($data) {
                $response = $userController->editUser($_GET['id'], $data);
            } else {
                $response = ['success' => false, 'error' => 'Datos inválidos.'];
            }
            break;

        case 'delete':
            if ($method !== 'DELETE' || !isset($_GET['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Acción no válida o falta ID.']);
                exit;
            }
            $response = $userController->removeUser($_GET['id']);
            break;

        default:
            http_response_code(400);
            $response = ['success' => false, 'error' => 'Acción no válida.'];
    }

    echo json_encode($response);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Error interno en el servidor', 'details' => $e->getMessage()]);
}
