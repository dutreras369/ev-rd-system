<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../controllers/RecordController.php';

// Inicializar controlador
$recordController = new RecordController();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($action === 'list') {
                if (!isset($_GET['user_id'], $_GET['token'], $_GET['page'], $_GET['limit'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $userId = $_GET['user_id'];
                $page = (int) $_GET['page'];
                $limit = (int) $_GET['limit'];
                $offset = ($page - 1) * $limit;

                // Obtener la fecha actual en formato YYYY-MM-DD
                $fecha = date('Y-m-d');

                // Obtener registros paginados
                $records = $recordController->listRecords($userId, $fecha, $limit, $offset);

                // Obtener el total de registros
                $totalRecords = $recordController->getTotalRecordsByUser($userId, $fecha);

                echo json_encode([
                    'success' => true,
                    'records' => $records,
                    'totalRecords' => $totalRecords,
                    'currentPage' => $page,
                    'totalPages' => ceil($totalRecords / $limit)
                ]);
                break;
            } elseif ($action === 'details') {
                if (!isset($_GET['user_id'], $_GET['token'], $_GET['record_id'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->getRecordDetails($_GET['record_id']);
                echo json_encode($response);
            } else {
                throw new Exception('Acción no válida para GET', 400);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);

            if ($action === 'register') {
                if (!isset($data['user_id'], $data['codigo_usuario'], $data['token'], $data['movement_type'], $data['amount'], $data['timestamp'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->createRecord($data);
                echo json_encode($response);

            } elseif ($action === 'list') {
                if (!isset($data['user_id'], $data['token'], $data['page'], $data['limit'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->listRecords($data['user_id'], $data['token'], $data['page'], $data['limit']);
                echo json_encode($response);

            } elseif ($action === 'update_status') {
                if (!isset($data['user_id'], $data['record_id'], $data['status'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->updateStatus($data['record_id'], $data['status']);
                echo json_encode($response);

            } elseif ($action === 'filter') {
                if (!isset($data['user_id'], $data['token'], $data['page'], $data['limit'])) {
                    throw new Exception('Datos incompletos.', 400);
                }

                $response = $recordController->filterRecords($data);
                echo json_encode($response);

            } else if ($action === 'total_records') {
                if (!$this->sessionService->validateToken($userId, $token)) {
                    return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
                }
            
                $offset = ($page - 1) * $limit;
                $fecha = date('Y-m-d');
            
                $records = $this->recordService->getRecordsByUser($userId, $fecha, $limit, $offset);
                $totalRecords = $this->recordService->getTotalRecordsByUser($userId, $fecha);
            
                return [
                    'success' => true,
                    'current_page' => $page,
                    'total_pages' => ceil($totalRecords / $limit),
                    'records' => array_map(function ($record) {
                        return [
                            'codigo_usuario' => $record['codigo_usuario'],
                            'tipo' => $record['tipo'],
                            'monto' => $record['monto'],
                            'fecha' => date('d-m-Y H:i:s', strtotime($record['fecha']))
                        ];
                    }, $records)
                ];

            } else {
                throw new Exception('Acción no válida para POST', 400);
            }
            break;

        default:
            throw new Exception('Método HTTP no permitido', 405);
    }
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
