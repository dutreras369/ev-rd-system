<?php
require_once __DIR__ . '/../config/Database.php';

class RecordService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function addRecord($userId, $type, $amount, $timestamp) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO registros (usuario_id, tipo, monto, fecha, estado) 
                VALUES (:user_id, :type, :amount, :timestamp, 'pendiente')
            ");
            $stmt->execute([
                ':user_id' => $userId,
                ':type' => $type,
                ':amount' => $amount,
                ':timestamp' => $timestamp
            ]);

            return ['success' => true, 'message' => 'Registro agregado con éxito'];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Error al registrar', 'details' => $e->getMessage()];
        }
    }

    public function getRecordsByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT tipo, monto, fecha FROM registros 
            WHERE usuario_id = :user_id 
            ORDER BY fecha DESC 
            LIMIT 10
        ");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
