<?php
require_once __DIR__ . '/../services/RecordService.php';
require_once __DIR__ . '/../services/ExcelService.php';


class FilterController
{
    private $recordService;
    private $excelService;

    public function __construct()
    {
        $this->recordService = new RecordService();
        $this->excelService = new ExcelService();

    }

    public function filterRecords($filters) {
        try {
            $result = $this->recordService->getFilteredRecords($filters);
    
            if ($result === false) {
                throw new Exception("Error en la consulta SQL.");
            }
    
            return [
                'success' => true,
                'records' => $result['records'],
                'total_records' => $result['total_records'],
                'current_page' => ($filters['offset'] / $filters['limit']) + 1,
                'total_pages' => ceil($result['total_records'] / $filters['limit'])
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Error al obtener registros.',
                'error_details' => $e->getMessage()
            ];
        }
    }


    /**
     * 🔹 Actualizar el estado de un registro.
     */
   /**
     * 🔹 Actualizar el estado de un registro filtrado.
     */
    public function updateRecordStatus($data) {
        try {
            if (!isset($data['record_id'], $data['status'])) {
                throw new Exception('Datos incompletos.');
            }

            // Verificar que el estado sea válido según ENUM ('pendiente', 'correcto', 'incorrecto')
            $validStatuses = ['pendiente', 'correcto', 'incorrecto'];
            if (!in_array($data['status'], $validStatuses)) {
                throw new Exception('Estado no válido.');
            }

            $recordId = (int) $data['record_id'];
            $newStatus = $data['status'];

            $result = $this->recordService->updateStatus($recordId, $newStatus);

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Estado actualizado correctamente.',
                    'record' => [
                        'id' => $recordId,
                        'estado' => $newStatus
                    ]
                ];
            } else {
                throw new Exception($result['error']);
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'No se pudo actualizar el estado.',
                'error_details' => $e->getMessage()
            ];
        }
    }

      /**
     * 🔹 Generar y descargar el archivo Excel.
     */
    public function exportExcel($filters) {
        $filePath = $this->excelService->generateExcel($filters);

        if ($filePath) {
            return [
                'success' => true,
                'file_url' => $filePath
            ];
        } else {
            return [
                'success' => false,
                'error' => 'No se encontraron registros para exportar.'
            ];
        }
    }
}
