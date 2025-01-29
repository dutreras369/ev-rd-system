<?php
header('Content-Type: application/json');
session_start(); // Asegurar que la sesión está iniciada

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../helpers/SessionManager.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// Obtener conexión con la base de datos
$pdo = Database::getConnection();
$authController = new AuthController($pdo);

$response = ['success' => false, 'error' => 'Acción no válida.'];

// 🔥 Unificar manejo de métodos GET y POST
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_GET['action'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Falta el parámetro "action".']);
        exit;
    }

    $action = $_GET['action'];

    switch ($action) {
        // ✅ 1️⃣ Estado de sesión (GET)
        case 'sessionStatus':
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                http_response_code(405);
                echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
                exit;
            }

            $response = [
                'success' => true,
                'is_authenticated' => SessionManager::isAuthenticated(),
                'user_id' => SessionManager::getAuthenticatedUserId(),
                'user_role' => SessionManager::getUserRole()
            ];
            break;

        // ✅ 2️⃣ Iniciar sesión (POST)
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
} else {
    http_response_code(405);
    $response = ['success' => false, 'error' => 'Método HTTP no permitido.'];
}

// 🚀 Devolver respuesta JSON
echo json_encode($response);
exit;
