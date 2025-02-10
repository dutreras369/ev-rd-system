<?php
require_once __DIR__ . '/../services/AnalysisService.php';
require_once __DIR__ . '/../services/SessionService.php';

class AnalysisController {
    private $analysisService;
    private $sessionService;

    public function __construct() {
        $this->analysisService = new AnalysisService();
        $this->sessionService = new SessionService();
    }

    public function getTotalStatusRecords() {
        $totales = $this->analysisService->getTotalStatusRecords();

        if (!$totales) {
            return ['success' => false, 'error' => 'No se pudieron obtener los totales'];
        }

        return [
            'success' => true,
            'total_cargas' => $totales['total_cargas'] ?? 0,
            'total_retiros' => $totales['total_retiros'] ?? 0,
            'total_registros' => $totales['total_registros'] ?? 0,
            'total_incorrectos' => $totales['total_incorrectos'] ?? 0
        ];
    }

    public function getUserRecords() {
        $records = $this->analysisService->getUserRecords();
    
        return [
            'success' => true,
            'records' => array_map(function ($record) {
                return [
                    'id' => $record['id'],
                    'nombre' => $record['nombre'],
                    'registros_mes' => $record['registros_mes'],
                    'correctos' => $record['correctos'],
                    'incorrectos' => $record['incorrectos']
                ];
            }, $records)
        ];
    }
    
}
