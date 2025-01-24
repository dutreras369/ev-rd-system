<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/init.php'; // Carga las configuraciones necesarias

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
    $action = $_GET['action'];
    
    $authController = new AuthController();

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
