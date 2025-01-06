<?php

class Utility {
    // Generar un identificador único
    public static function generateUUID() {
        return bin2hex(random_bytes(16));
    }

    // Sanitizar entrada de datos
    public static function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }

    // Validar correos electrónicos
    public static function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Redondear un número a dos decimales
    public static function roundToTwoDecimals($number) {
        return round($number, 2);
    }
}
