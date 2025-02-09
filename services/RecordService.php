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

    public function getRecordsByUser($userId, $fecha, $limit = 10, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT * FROM registros 
            WHERE usuario_id = :user_id 
            AND DATE(fecha) = :fecha
            ORDER BY fecha DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }   
    
    
    // Contar el total de registros para la paginación
    public function getTotalRecordsByUser($userId, $fecha) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as total FROM registros 
            WHERE usuario_id = :user_id 
            AND DATE(fecha) = :fecha
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':fecha' => $fecha
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
    }    

    // Contar el total de registros para la paginación
    public function getTotalStatusRecordsByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'correcto' THEN 1 ELSE 0 END) AS correct,
                SUM(CASE WHEN estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrect
            FROM registros
            WHERE usuario_id = :user_id AND DATE(fecha) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        ");
        $stmt->execute(['user_id' => $userId]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getRecordsBySession($userId, $sessionStartTime, $limit = 10, $offset = 0) {
        $dateOnly = date('Y-m-d', strtotime($sessionStartTime)); // Extraer solo la fecha
    
        $stmt = $this->pdo->prepare("
            SELECT * FROM registros 
            WHERE usuario_id = :user_id 
              AND DATE(fecha) = :session_date
            ORDER BY fecha DESC
            LIMIT :limit OFFSET :offset
        ");
    
        // Convertir `LIMIT` y `OFFSET` a enteros para evitar errores
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':session_date', $dateOnly, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    
        $stmt->execute();
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($recordId, $status) {
        $stmt = $this->pdo->prepare("UPDATE registros SET estado = :status WHERE id = :record_id");
        $stmt->execute([
            ':status' => $status,
            ':record_id' => $recordId
        ]);
    
        return ['success' => $stmt->rowCount() > 0];
    }

    public function getRecordDetails($recordId) {
        $stmt = $this->pdo->prepare("SELECT * FROM registros WHERE id = :record_id");
        $stmt->execute([':record_id' => $recordId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

    public function filterRecords($filters) {
        $query = "SELECT * FROM registros WHERE 1=1";
        $params = [];
    
        if (!empty($filters['user_id'])) {
            $query .= " AND usuario_id = :user_id";
            $params[':user_id'] = $filters['user_id'];
        }
    
        if (!empty($filters['date_from'])) {
            $query .= " AND DATE(fecha) >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
    
        if (!empty($filters['date_to'])) {
            $query .= " AND DATE(fecha) <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
    
        if (!empty($filters['status'])) {
            $query .= " AND estado = :status";
            $params[':status'] = $filters['status'];
        }
    
        // Agregar paginación
        if (isset($filters['limit'], $filters['offset'])) {
            $query .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $filters['limit'];
            $params[':offset'] = $filters['offset'];
        }
    
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalStatusRecords() {
        $stmt = $this->pdo->prepare("
            SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'correcto' THEN 1 ELSE 0 END) AS correct,
                SUM(CASE WHEN estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrect
            FROM registros
            WHERE DATE(fecha) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
       
    public function getTotalRecords($userId, $fecha) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM registros WHERE usuario_id = :user_id AND DATE(fecha) = :fecha");
        $stmt->execute([
            ':user_id' => $userId,
            ':fecha' => $fecha
        ]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }    
}
