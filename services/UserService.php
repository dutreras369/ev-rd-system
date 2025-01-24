<?php

require_once __DIR__ . '/../models/User.php';

class UserService
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new User($data), $users);
    }

    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new User($data) : null;
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM usuarios 
            WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Registrar log
        Logger::info("Consulta de usuario por email: $email}");
    
        return $data ? new User($data) : null;
    }
    
    public function addUser(User $user)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO usuarios (nombre, username, email, contrasena, rol, hora_inicio, hora_fin, estado)
            VALUES (:nombre, :username, :email, :contrasena, :rol, :hora_inicio, :hora_fin, :estado)
        ");
        $stmt->execute([
            'nombre' => $user->nombre,
            'username' => $user->username,
            'email' => $user->email,
            'contrasena' => password_hash($user->contrasena, PASSWORD_BCRYPT),
            'rol' => $user->rol,
            'hora_inicio' => $user->hora_inicio,
            'hora_fin' => $user->hora_fin,
            'estado' => $user->estado,
        ]);

        $userId = $this->pdo->lastInsertId();

        // Registrar log usando Logger
        Logger::info("Usuario agregado: {$user->email} (ID: $userId)");

        return $userId;
    }

    public function updateUser(User $user)
    {
        $stmt = $this->pdo->prepare("
            UPDATE usuarios
            SET nombre = :nombre, username = :username, email = :email, rol = :rol, hora_inicio = :hora_inicio, hora_fin = :hora_fin, estado = :estado
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $user->id,
            'nombre' => $user->nombre,
            'username' => $user->username,
            'email' => $user->email,
            'rol' => $user->rol,
            'hora_inicio' => $user->hora_inicio,
            'hora_fin' => $user->hora_fin,
            'estado' => $user->estado,
        ]);

        Logger::info("Usuario Actualizado: {$user->email} (ID: $user->id)");

        return $stmt->rowCount();
    }

    public function canLoginToday($userId) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM registros 
            WHERE usuario_id = :userId 
            AND DATE(fecha) = CURDATE()
        ");
        $stmt->execute(['userId' => $userId]);
        $count = $stmt->fetchColumn();
    
        // Registrar log de verificación
        Logger::info("Verificación de inicio de sesión para usuario ID: $userId");
    
        // Retorna true si no hay registros para hoy
        return $count == 0;
    }
    
    public function deleteUser($id) {
        $user = $this->getUserById($id); // Obtener información del usuario antes de eliminar
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        
        // Registrar log
        Logger::info("Usuario eliminado: {$user->email}", $id);
        
        return $stmt->rowCount();
    }

    public function logEvent($accion, $usuarioId = null)
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconocido';

        $query = "INSERT INTO logs (accion, usuario_id, ip_address, user_agent) VALUES (:accion, :usuario_id, :ip_address, :user_agent)";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute([
            ':accion' => $accion,
            ':usuario_id' => $usuarioId,
            ':ip_address' => $ipAddress,
            ':user_agent' => $userAgent,
        ]);
    }
}
