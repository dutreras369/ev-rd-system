<?php

require_once __DIR__ . '/constants.php';

// Autoload de clases para simplificar dependencias
require_once __DIR__ . '/autoload.php';

// Clase de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Servicio de Logs
require_once __DIR__ . '/../services/LogService.php';

session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

$user_id = SessionManager::getAuthenticatedUserId();
$user_role = SessionManager::getUserRole();
$is_authenticated = SessionManager::isAuthenticated();

echo "<pre>User ID: " . ($user_id ?? 'No data') . "</pre>";
echo "<pre>User Role: " . ($user_role ?? 'No data') . "</pre>";
echo "<pre>Authenticated: " . ($is_authenticated ? 'Yes' : 'No') . "</pre>";
