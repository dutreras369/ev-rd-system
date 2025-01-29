<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// Obtener conexión con la base de datos
$pdo = Database::getConnection();
$authController = new AuthController();

$response = ['success' => false, 'error' => 'Acción no válida.'];

if (!isset($_GET['action'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Falta el parámetro "action".']);
    exit;
}

$action = $_GET['action'];

switch ($action) {
    case 'sessionStatus':
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
            exit;
        }
        $response = $authController->sessionStatus();
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
            exit;
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if ($email && $password) {
            $response = $authController->login($email, $password);
        } else {
            $response = ['success' => false, 'error' => 'Faltan datos.'];
        }
        break;

    default:
        http_response_code(400);
        $response = ['success' => false, 'error' => 'Acción no válida.'];
}

echo json_encode($response);
exit;
