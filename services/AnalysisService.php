<?php
require_once __DIR__ . '/../config/Database.php';

class AnalysisService {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getTotalStatusRecords() {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    COUNT(*) AS total,
                    SUM(CASE WHEN estado = 'correcto' THEN 1 ELSE 0 END) AS correct,
                    SUM(CASE WHEN estado = 'incorrecto' THEN 1 ELSE 0 END) AS incorrect
                FROM registros
                WHERE DATE(fecha) = DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return ['total' => 0, 'correct' => 0, 'incorrect' => 0];
            }

            return $result;
        } catch (PDOException $e) {
            error_log("Error en getTotalStatusRecords: " . $e->getMessage());
            return false;
        }
    }
}
