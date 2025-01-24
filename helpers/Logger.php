<?php

require_once __DIR__ . '/../config/constants.php';

class Logger {
    private static $logDir = LOG_DIR; // Usar la constante definida
    private static $logFile = 'system.log';

    public static function log($message, $level = 'INFO') {
        // Asegurarse de que el directorio existe
        if (!is_dir(self::$logDir)) {
            if (!mkdir(self::$logDir, 0755, true) && !is_dir(self::$logDir)) {
                error_log("No se pudo crear el directorio de logs: " . self::$logDir);
                return;
            }
        }
    
        $filePath = self::$logDir . '/' . self::$logFile;
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp][$level] $message" . PHP_EOL;
    
        // Intentar escribir en el archivo
        if (file_put_contents($filePath, $formattedMessage, FILE_APPEND) === false) {
            error_log("No se pudo escribir en el archivo de logs: $filePath");
        }
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
