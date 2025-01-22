<?php
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/config/init.php'; // Carga PDO y otras configuraciones

// Configurar respuesta JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];

    $authController = new AuthController($pdo);

    switch ($action) {
        case 'login':
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $response = $authController->login($email, $password);
            echo json_encode($response);
            break;

        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
            break;
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método HTTP no permitido.']);
}
