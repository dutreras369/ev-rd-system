<?php
require_once __DIR__ . '/../services/RecordService.php';
require_once __DIR__ . '/../services/SessionService.php';

class RecordController {
    private $recordService;
    private $sessionService;

    public function __construct() {
        $this->recordService = new RecordService();
        $this->sessionService = new SessionService();
    }

    public function createRecord($data)
    {
        // Validar sesión con token
        if (!$this->sessionService->validateToken($data['user_id'], $data['token'])) {
            return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
        }

        // Registrar en la BD
        return $this->recordService->addRecord(
            $data['user_id'],
            $data['codigo_usuario'],
            $data['movement_type'],
            $data['amount'],
            $data['timestamp']
        );
    }

    public function listRecords($userId) {
        return [
            'success' => true,
            'records' => $this->recordService->getRecordsByUser($userId)
        ];
    }
}
