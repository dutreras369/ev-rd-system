<?php
require_once __DIR__ . '/../services/RecordService.php';

class FilterController
{
    private $recordService;

    public function __construct()
    {
        $this->recordService = new RecordService();
    }

    public function filterRecords($filters) {
        try {
            $result = $this->recordService->getFilteredRecords($filters);
    
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
}
