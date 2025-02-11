<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/UserController.php';

$userController = new UserController();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if ($action === 'list_users') {
                if (!isset($data['user_id'], $data['token'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $userController->listUsers();
                echo json_encode($response);
            } 
            elseif ($action === 'get_user') {
                if (!isset($data['nombre'], $data['email'], $data['rol_id'], $data['contrasena'])) {
                    echo json_encode(['success' => false, 'error' => 'Faltan datos obligatorios.']);
                    exit;
                }

                $response = $userController->getUser($data['user_id'], $data['token']);
                echo json_encode($response);
            } elseif ($action === 'add') {
                if (!isset($data['user_id'], $data['token'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $userController->createUser($data);
                echo json_encode($response);
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
