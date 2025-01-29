<?php
require_once __DIR__ . '/../helpers/SessionManager.php';

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Responder con los datos de sesión
echo json_encode([
    'is_authenticated' => SessionManager::isAuthenticated(),
    'user_id' => SessionManager::getAuthenticatedUserId(),
    'user_role' => SessionManager::getUserRole()
]);
?>
