<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/RecordService.php'; // Incluir el servicio que obtiene los registros

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelService {
    private $recordService;

    public function __construct() {
        $this->recordService = new RecordService();
    }

    public function generateExcel($filters) {
        // Obtener registros SIN paginación
        $data = $this->recordService->getFilteredRecords($filters, true);
    
        if (!$data || empty($data['records'])) {
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
