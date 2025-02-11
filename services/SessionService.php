<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../services/LogService.php';

class SessionService
{
    private $pdo;
    private $logService;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->logService = new LogService(); // Inicializamos el servicio de logs
    }

    // Registrar sesión en la base de datos
    public function createSession($userId, $roleId, $token, $ip)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO sesiones (usuario_id, rol_id, ip_address, inicio, token) 
                                         VALUES (:user_id, :role_id, :ip, NOW(), :token)");
            $stmt->execute([
                ':user_id' => $userId,
                ':role_id' => $roleId,
                ':ip' => $ip,
                ':token' => $token
            ]);

            $this->logService->addLog("Nueva sesion creada para el usuario ID: $userId", $userId);
            return true;
        } catch (PDOException $e) {
            $this->logService->addLog("Error al crear sesion para usuario ID: $userId - " . $e->getMessage(), $userId);
            return false;
        }
    }

    // Cerrar sesión en la base de datos
    public function closeSession($userId, $token)
    {
        try {
            $stmt = $this->pdo->prepare("UPDATE sesiones SET fin = NOW() WHERE usuario_id = :user_id AND token = :token AND fin IS NULL");
            $stmt->execute([
                ':user_id' => $userId,
                ':token' => $token
            ]);

            if ($stmt->rowCount() > 0) {
                $this->logService->addLog("Sesion cerrada para usuario ID: $userId", $userId);
                return true;
            } else {
                $this->logService->addLog("Intento de cierre de sesión fallido para usuario ID: $userId", $userId);
                return false;
            }
        } catch (PDOException $e) {
            $this->logService->addLog("Error al cerrar sesion para usuario ID: $userId - " . $e->getMessage(), $userId);
            return false;
        }
    }

    // Obtener el nombre del rol
    public function getRoleName($roleId)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT nombre FROM roles WHERE id = :role_id");
            $stmt->execute(['role_id' => $roleId]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            return $role['nombre'] ?? null;
        } catch (PDOException $e) {
            $this->logService->addLog("Error al obtener el rol ID: $roleId - " . $e->getMessage(), null);
            return null;
        }
    }

    // Validar si el token de sesión es válido y está activo
    public function validateToken($userId, $token)
    {
        $stmt = $this->pdo->prepare("
            SELECT id FROM sesiones 
            WHERE usuario_id = :user_id 
              AND token = :token 
              AND fin IS NULL
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token
        ]);
    
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
    
    public function getSessionStartTime($userId, $token)
    {
        $stmt = $this->pdo->prepare("
            SELECT inicio FROM sesiones 
            WHERE usuario_id = :user_id 
              AND token = :token 
              AND fin IS NULL
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token
        ]);
    
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        return $session ? $session['inicio'] : null;
    }
    
}
