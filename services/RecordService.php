<?php
require_once __DIR__ . '/../config/Database.php';

class RecordService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function addRecord($userId, $codigoUsuario, $movementType, $amount, $timestamp)
    {
        $stmt = $this->pdo->prepare("INSERT INTO registros (usuario_id, codigo_usuario, tipo, monto, fecha) 
                                     VALUES (:usuario_id, :codigo_usuario, :tipo, :monto, :fecha)");

        $success = $stmt->execute([
            ':usuario_id' => $userId,
            ':codigo_usuario' => $codigoUsuario,
            ':tipo' => $movementType,
            ':monto' => $amount,
            ':fecha' => $timestamp
        ]);

        return ['success' => $success];
    }

    public function getRecordsByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT codigo_usuario, tipo, monto, fecha 
            FROM registros 
            WHERE usuario_id = :user_id 
            ORDER BY fecha DESC
        ");
        $stmt->execute(['user_id' => $userId]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecordsBySession($userId, $sessionStartTime) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM registros 
            WHERE usuario_id = :user_id 
              AND fecha >= :session_start_time
            ORDER BY fecha DESC
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':session_start_time' => $sessionStartTime
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
}
