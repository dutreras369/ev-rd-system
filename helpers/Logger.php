<?php

require_once __DIR__ . '/../config/constants.php';

class Logger {
    private static $logDir = LOG_DIR; // Usar la constante definida
    private static $logFile = 'system.log';

    public static function log($message) {
        // Asegurarse de que el directorio existe
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0755, true); // Crear directorio con permisos
        }

        $filePath = self::$logDir . '/' . self::$logFile;
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] $message" . PHP_EOL;

        // Guardar el mensaje en el archivo de log
        file_put_contents($filePath, $formattedMessage, FILE_APPEND);
    }

    // Métodos de conveniencia para diferentes tipos de logs
    public static function info($message) {
        self::log($message, 'INFO');
    }

    public static function warning($message) {
        self::log($message, 'WARNING');
    }

    public static function error($message) {
        self::log($message, 'ERROR');
    }
}
