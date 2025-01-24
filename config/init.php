<?php

require_once __DIR__ . '/constants.php';

// Autoload de clases para simplificar dependencias
require_once __DIR__ . '/autoload.php';

// Clase de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Log de inicialización
require_once __DIR__ . '/../helpers/Logger.php';

// Iniciañizar sesion
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Logger::info("Sistema inicializado correctamente.");
