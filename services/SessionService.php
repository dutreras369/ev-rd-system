<?php

require_once __DIR__ . '/../config/Database.php';

class SessionService
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Registrar sesión en la base de datos
    public function createSession($userId, $roleId, $token, $ip)
    {
        $stmt = $this->pdo->prepare("INSERT INTO sesiones (usuario_id, rol_id, ip_address, inicio, token) 
                                     VALUES (:user_id, :role_id, :ip, NOW(), :token)");
        return $stmt->execute([
            ':user_id' => $userId,
            ':role_id' => $roleId,
            ':ip' => $ip,
            ':token' => $token
        ]);
    }

    // Cerrar sesión en la base de datos
    public function closeSession($userId, $token)
    {
        $stmt = $this->pdo->prepare("UPDATE sesiones SET fin = NOW() WHERE usuario_id = :user_id AND token = :token AND fin IS NULL");
        $stmt->execute([
            ':user_id' => $userId,
            ':token' => $token
        ]);

        return $stmt->rowCount() > 0;
    }

    // Obtener el nombre del rol
    public function getRoleName($roleId)
    {
        $stmt = $this->pdo->prepare("SELECT nombre FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role['nombre'] ?? null;
    }
}
