<?php

require_once __DIR__ . '/../models/Log.php';

class LogService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection(); // Usar la conexión a través de Database.php
    }

    /**
     * Registrar un log en la base de datos.
     */
    public function addLog($accion, $usuario_id = null) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';

        $stmt = $this->pdo->prepare("
            INSERT INTO logs (accion, usuario_id, ip_address, user_agent)
            VALUES (:accion, :usuario_id, :ip_address, :user_agent)
        ");
        $stmt->execute([
            'accion' => $accion,
            'usuario_id' => $usuario_id,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
        ]);
    }

    /**
     * Obtener todos los logs.
     */
    public function getAllLogs() {
        $stmt = $this->pdo->query("SELECT * FROM logs ORDER BY fecha DESC");
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($data) => new Log($data), $logs);
    }

    /**
     * Obtener logs de un usuario específico.
     */
    public function getLogsByUserId($usuario_id) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM logs WHERE usuario_id = :usuario_id ORDER BY fecha DESC
        ");
        $stmt->execute(['usuario_id' => $usuario_id]);

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new Log($data), $logs);
    }
}
