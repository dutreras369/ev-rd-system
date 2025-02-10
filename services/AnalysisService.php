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
                    SUM(CASE WHEN tipo = 'carga' THEN monto ELSE 0 END) AS total_cargas,
                    SUM(CASE WHEN tipo = 'retiro' THEN monto ELSE 0 END) AS total_retiros,
                    COUNT(*) AS total_registros,
                    SUM(CASE WHEN estado = 'incorrecto' THEN 1 ELSE 0 END) AS total_incorrectos
                FROM registros
                WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$result) {
                return [
                    'total_cargas' => 0,
                    'total_retiros' => 0,
                    'total_registros' => 0,
                    'total_incorrectos' => 0
                ];
            }
    
            return $result;
        } catch (PDOException $e) {
            error_log("Error en getTotalStatusRecords: " . $e->getMessage());
            return false;
        }
    }
    
}
