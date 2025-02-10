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
            'total_records' => $totales['total'] ?? 0,
            'total_correct' => $totales['correct'] ?? 0,
            'total_incorrect' => $totales['incorrect'] ?? 0
        ];
    }
}
