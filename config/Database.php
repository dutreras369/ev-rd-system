<?php

class Database {
    // Instancia única de PDO
    private static $instance = null;

    // Constructor privado para evitar instancias externas
    private function __construct() {}

    /**
     * Obtiene la instancia única de PDO.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function getConnection() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                    DB_USER,
                    DB_PASS
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Manejo de errores
                die("Error en la conexión: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
