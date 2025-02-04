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

    public function listRecords($userId, $token) {
        // Validar si el token es válido
        if (!$this->sessionService->validateToken($userId, $token)) {
            return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
        }
    
        // Obtener la fecha de inicio de la sesión activa
        $sessionStartTime = $this->sessionService->getSessionStartTime($userId, $token);
        if (!$sessionStartTime) {
            return ['success' => false, 'error' => 'No se encontró una sesión activa.'];
        }
    
        // Obtener registros filtrados por ID de usuario y fecha de inicio de sesión
        $records = $this->recordService->getRecordsBySession($userId, $sessionStartTime);
    
        if ($records) {
            return [
                'success' => true,
                'records' => array_map(function ($record) {
                    return [
                        'codigo_usuario' => $record['codigo_usuario'], // Incluir código del usuario
                        'tipo' => $record['tipo'],
                        'monto' => $record['monto'],
                        'fecha' => date('d-m-Y H:i:s', strtotime($record['fecha'])) // Formatear la fecha correctamente
                    ];
                }, $records)
            ];
        }
    
        return ['success' => false, 'error' => 'No hay registros para este usuario en la sesión actual.'];
    }
    
    
}
