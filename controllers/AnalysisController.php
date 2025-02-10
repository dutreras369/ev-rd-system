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
}
