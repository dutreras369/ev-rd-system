<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelService {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAllFilteredRecords($filters) {
        try {
            // Consulta sin paginación
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

            $query .= " ORDER BY r.fecha DESC"; // Sin paginación

            $stmt = $this->pdo->prepare($query);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getAllFilteredRecords: " . $e->getMessage());
            return false;
        }
    }

    public function generateExcel($filters) {
        // Obtener registros sin paginación
        $records = $this->getAllFilteredRecords($filters);

        if (!$records || empty($records)) {
            return false; // No hay registros para exportar
        }
        
        $records = $data['records'];
    
        // 🔹 Crear hoja de cálculo
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        // 🔹 Encabezados de la tabla
        $headers = ["Trabajador", "Usuario", "Código Usuario", "Fecha", "Tipo", "Monto", "Estado"];
        $sheet->fromArray([$headers], NULL, 'A1');
    
        // 🔹 Agregar datos a la tabla
        $rowIndex = 2;
        foreach ($records as $record) {
            $sheet->fromArray([
                $record['trabajador_nombre'] ?? 'N/A',
                $record['usuario_nombre'] ?? 'N/A',
                $record['codigo_usuario'] ?? 'N/A',
                $record['fecha'],
                $record['tipo'],
                number_format($record['monto'], 2, '.', ','), // Formato correcto de moneda
                ucfirst($record['estado']) // Primera letra en mayúscula
            ], NULL, "A$rowIndex");
    
            $rowIndex++;
        }
    
        // 🔹 Asegurar que la carpeta `exports/` existe
        $exportPath = __DIR__ . '/../exports/';
        if (!is_dir($exportPath)) {
            mkdir($exportPath, 0775, true);
        }
    
        // 🔹 Guardar archivo con timestamp único
        $fileName = 'exported_records_' . date('Ymd_His') . '.xlsx';
        $filePath = $exportPath . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    
        return "exports/" . $fileName;
    }
    
}
