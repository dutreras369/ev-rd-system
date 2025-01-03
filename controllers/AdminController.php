<?php
require_once __DIR__ . '/../config/init.php'; // Configuración inicial
require_once __DIR__ . '/../models/Record.php'; // Modelo de registros

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Conexión a la base de datos
        $db = new PDO("mysql:host=localhost;dbname=ev_rd_system", "root", "password"); // Ajusta con tus credenciales
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Instancia del modelo
        $recordModel = new Record($db);

        // Obtener filtros desde la solicitud
        $filters = [
            'user' => $_POST['user'] ?? null,
            'start_date' => $_POST['start_date'] ?? null,
            'end_date' => $_POST['end_date'] ?? null,
            'type' => $_POST['type'] ?? null,
            'status' => $_POST['status'] ?? null,
        ];

        // Obtener registros filtrados
        $records = $recordModel->getFilteredRecords($filters);

        // Devolver los datos en formato JSON
        echo json_encode($records);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
    }
    exit;
}

// Si no es una solicitud POST, devolver error
http_response_code(405);
echo json_encode(['error' => 'Método no permitido']);
exit;
