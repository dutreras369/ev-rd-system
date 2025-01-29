<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    session_start(); // Asegurar que la sesión está iniciada
    require_once __DIR__ . '/../helpers/SessionManager.php';

    switch ($action) {
        case 'sessionStatus':
            echo json_encode([
                'success' => true,
                'is_authenticated' => SessionManager::isAuthenticated(),
                'user_id' => SessionManager::getAuthenticatedUserId(),
                'user_role' => SessionManager::getUserRole()
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];
    require_once __DIR__ . '/../controllers/AuthController.php';

    $authController = new AuthController($pdo);

    switch ($action) {
        case 'login':
            $email = $_POST['email'] ?? null;
            $password = $_POST['password'] ?? null;

            if ($email && $password) {
                $response = $authController->login($email, $password);
                echo json_encode($response);
            } else {
                echo json_encode(['success' => false, 'error' => 'Faltan datos.']);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
}
