<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelService {
    public function generateExcel($records) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 🔹 Encabezados
        $headers = ["Trabajador", "Usuario", "Fecha", "Tipo", "Monto", "Estado"];
        $sheet->fromArray([$headers], NULL, 'A1');

        // 🔹 Agregar datos
        $rowIndex = 2;
        foreach ($records as $record) {
            $sheet->fromArray([
                $record['trabajador_nombre'] ?? 'N/A',
                $record['usuario_nombre'] ?? 'N/A',
                $record['fecha'],
                $record['tipo'],
                $record['monto'],
                $record['estado']
            ], NULL, "A$rowIndex");

            $rowIndex++;
        }

        // 🔹 Asegurar que la carpeta exports/ existe
        $exportPath = __DIR__ . '/../exports/';
        if (!is_dir($exportPath)) {
            mkdir($exportPath, 0775, true);
        }

        // 🔹 Guardar archivo con timestamp usando `date()`
        $fileName = 'exported_records_' . date('Ymd_His') . '.xlsx';
        $filePath = $exportPath . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return "exports/" . $fileName;
    }
}
