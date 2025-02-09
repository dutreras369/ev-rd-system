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

        return [
            'success' => true,
            'total_records' => $totales['total'],
            'total_correct' => $totales['correct'],
            'total_incorrect' => $totales['incorrect']
        ];
    }
}
