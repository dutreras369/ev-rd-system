<?php
require_once __DIR__ . '/../services/RecordService.php';
require_once __DIR__ . '/../services/SessionService.php';

class RecordController
{
    private $recordService;
    private $sessionService;

    public function __construct()
    {
        $this->recordService = new RecordService();
        $this->sessionService = new SessionService();
    }

    public function createRecord($data)
    {
        // Validar sesión con token
        if (!$this->sessionService->validateToken($data['user_id'], $data['token'])) {
            return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
        }

        // Validar existencia de código de usuario
        if (!isset($data['codigo_usuario']) || empty($data['codigo_usuario'])) {
            return ['success' => false, 'error' => 'El código de usuario es obligatorio.'];
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

    public function listRecords($userId, $token, $page, $limit) {
        // Validar token de sesión
        if (!$this->sessionService->validateToken($userId, $token)) {
            return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
        }
    
        // Calcular el offset para la paginación
        $offset = ($page - 1) * $limit;
        $fecha = date('Y-m-d'); // Obtener solo la fecha actual
    
        // Obtener registros
        $records = $this->recordService->getRecordsByUser($userId, $fecha, $limit, $offset);
        $totalRecords = $this->recordService->getTotalRecordsByUser($userId, $fecha);
    
        // Calcular total de páginas
        $totalPages = ceil($totalRecords / $limit);
    
        if ($records) {
            return [
                'success' => true,
                'current_page' => $page,
                'total_pages' => $totalPages,
                'records' => array_map(function ($record) {
                    return [
                        'codigo_usuario' => $record['codigo_usuario'],
                        'tipo' => $record['tipo'],
                        'monto' => $record['monto'],
                        'fecha' => date('d-m-Y H:i:s', strtotime($record['fecha']))
                    ];
                }, $records)
            ];
        }
    
        return ['success' => false, 'error' => 'No hay registros para este usuario.'];
    }
    
    
    public function getTotalRecordsByUser($userId, $fecha) {
        return $this->recordService->getTotalRecordsByUser($userId, $fecha);
    }

    public function updateStatus($recordId, $status) {
        return $this->recordService->updateStatus($recordId, $status);
    }

    public function getRecordDetails($recordId) {
        return $this->recordService->getRecordDetails($recordId);
    }
    public function filterRecords($filters) {
        return $this->recordService->filterRecords($filters);
    }
        
    public function getTotalRecords($userId, $fecha) {
        return $this->recordService->getTotalRecords($userId, $fecha);
    }
}
