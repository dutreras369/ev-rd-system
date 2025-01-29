<?php

require_once __DIR__ . '/constants.php';

// Autoload de clases para simplificar dependencias
require_once __DIR__ . '/autoload.php';

// Clase de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Servicio de Logs
require_once __DIR__ . '/../services/LogService.php';

// Sesion Manager
require_once __DIR__ . '/../helpers/SessionManager.php';

$user_id = SessionManager::getAuthenticatedUserId();
$user_role = SessionManager::getUserRole();
$is_authenticated = SessionManager::isAuthenticated();

session_start();
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>

<pre><?php echo $user_id; ?></pre>
<pre><?php echo $user_role; ?></pre>
<pre><?php echo $user_role; ?></pre>