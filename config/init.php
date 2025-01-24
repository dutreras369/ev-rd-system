<?php

require_once __DIR__ . '/constants.php';

// Autoload de clases para simplificar dependencias
require_once __DIR__ . '/autoload.php';

// Clase de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Servicio de Logs
require_once __DIR__ . '/../services/LogService.php';

// Inicializar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Probar conexión a la base de datos
    $pdo = Database::getConnection();

    // Probar el servicio de logs
    $logService = new LogService();

    // Insertar un log de prueba en la base de datos
    $logService->logEvent('Sistema inicializado correctamente.', null);

    // Mensaje en la consola del servidor
    echo "Sistema inicializado y log insertado en la base de datos correctamente.";
} catch (PDOException $e) {
    // Registrar un log en caso de error en la conexión
    $logService = isset($logService) ? $logService : null;

    if ($logService) {
        $logService->logEvent('Error al conectar con la base de datos: ' . $e->getMessage(), null);
    }

    die("No se pudo establecer conexión con la base de datos. Verifica la configuración.");
} catch (Exception $e) {
    // Manejar cualquier otro error durante la inicialización
    if (isset($logService)) {
        $logService->logEvent('Error general en la inicialización: ' . $e->getMessage(), null);
    }

    die("Ocurrió un error durante la inicialización del sistema.");
}
