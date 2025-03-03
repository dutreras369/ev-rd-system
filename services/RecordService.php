<?php
require_once __DIR__ . '/../config/Database.php';

class RecordService
{
    private $pdo;

    public function __construct()
    {
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

    public function getRecordsByUser($userId, $fecha, $limit = 10, $offset = 0)
    {
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
    public function getTotalRecordsByUser($userId, $fecha)
    {
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
    public function getTotalStatusRecordsByUser($userId)
    {
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

    public function getRecordsBySession($userId, $sessionStartTime, $limit = 10, $offset = 0)
    {
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

    /**
     * 🔹 Actualizar el estado de un registro.
     */
    public function updateStatus($recordId, $newStatus) {
        try {
            $stmt = $this->pdo->prepare("UPDATE registros SET estado = :status WHERE id = :record_id");
            $stmt->execute([
                ':status' => $newStatus,
                ':record_id' => $recordId
            ]);

            if ($stmt->rowCount() > 0) {
                return ['success' => true];
            } else {
                return ['success' => false, 'error' => 'No se encontró el registro o el estado ya estaba actualizado.'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getRecordDetails($recordId)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM registros WHERE id = :record_id");
        $stmt->execute([':record_id' => $recordId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFilteredRecords($filters, $forExport = false) {
        try {
            // Consulta para obtener registros
            $query = "
                SELECT 
                    r.id, 
                    u.nombre AS trabajador_nombre, 
                    c.nombre AS usuario_nombre, 
                    r.codigo_usuario,
                    r.tipo, 
                    r.monto, 
                    r.descripcion, 
                    r.fecha, 
                    r.estado
                FROM registros r
                LEFT JOIN usuarios u ON r.usuario_id = u.id 
                LEFT JOIN usuarios c ON r.codigo_usuario = c.id 
                WHERE 1=1";
            
            $params = [];
    
            if (!empty($filters['user_id'])) {
                $query .= " AND r.usuario_id = :user_id";
                $params[':user_id'] = $filters['user_id'];
            }
    
            if (!empty($filters['date_from'])) {
                $query .= " AND DATE(r.fecha) >= :date_from";
                $params[':date_from'] = $filters['date_from'];
            }
    
            if (!empty($filters['date_to'])) {
                $query .= " AND DATE(r.fecha) <= :date_to";
                $params[':date_to'] = $filters['date_to'];
            }
    
            if (!empty($filters['type'])) {
                $query .= " AND r.tipo = :type";
                $params[':type'] = $filters['type'];
            }
    
            if (!empty($filters['status'])) {
                $query .= " AND r.estado = :status";
                $params[':status'] = $filters['status'];
            }
    
            // Solo agregar paginación si **NO** es una exportación
            if (!$forExport) {
                $query .= " ORDER BY r.fecha DESC LIMIT :limit OFFSET :offset";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindValue(':limit', (int) $filters['limit'], PDO::PARAM_INT);
                $stmt->bindValue(':offset', (int) $filters['offset'], PDO::PARAM_INT);
            } else {
                $query .= " ORDER BY r.fecha DESC"; // Sin limit ni offset para exportación
                $stmt = $this->pdo->prepare($query);
            }
    
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
    
            $stmt->execute();
            return [
                'records' => $stmt->fetchAll(PDO::FETCH_ASSOC)
            ];
        } catch (PDOException $e) {
            error_log("Error en getFilteredRecords: " . $e->getMessage());
            return false;
        }
    }    
    
    public function getTotalStatusRecords()
    {
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

    public function getTotalRecords($userId, $fecha)
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM registros WHERE usuario_id = :user_id AND DATE(fecha) = :fecha");
        $stmt->execute([
            ':user_id' => $userId,
            ':fecha' => $fecha
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
