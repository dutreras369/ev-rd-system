<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../services/LogService.php';

class UserService
{
    private $pdo;
    private $logService;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
        $this->logService = new LogService(); // Inicializamos el servicio de logs
    }

    public function getAllUsers()
    {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->logService->addLog("Consulta de todos los usuarios", null); // Log sin usuario específico

        return array_map(fn($data) => new User($data), $users);
    }

    public function getUsers()
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, nombre, email, 
                       CASE 
                           WHEN rol_id = 1 THEN 'Administrador' 
                           ELSE 'Usuario' 
                       END AS rol 
                FROM usuarios
                ORDER BY nombre ASC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en getUsers: " . $e->getMessage());
            return [];
        }
    }


    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            return new User($data); // Devuelve una instancia de User
        }
        return null;
    }


    public function getUserByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM usuarios 
            WHERE email = :email
        ");
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $this->logService->addLog("Consulta de usuario por email: $email", $data['id']);
        } else {
            $this->logService->addLog("Consulta fallida para email: $email", null);
        }

        return $data ? new User($data) : null;
    }

    public function addUser(User $user, $userId)
    {
        try {
            $this->logService->addLog("Consulta de usuario por username: $user->username, email: $user->email, rol_id: $user->rol_id", $userId);


            // 🔹 Prepara la consulta SQL
            $stmt = $this->pdo->prepare("
        INSERT INTO usuarios (nombre, username, email, contrasena, rol_id, hora_inicio, hora_fin, estado)
        VALUES (:nombre, :username, :email, :contrasena, :rol_id, :hora_inicio, :hora_fin, :estado)
    ");

            $success = $stmt->execute([
                ':nombre' => $user->nombre,
                ':username' => $user->username,
                ':email' => $user->email,
                ':contrasena' => password_hash($user->contrasena, PASSWORD_BCRYPT),
                ':rol_id' => $user->rol_id,
                ':hora_inicio' => $user->hora_inicio ?? null,
                ':hora_fin' => $user->hora_fin ?? null,
                ':estado' => $user->estado ?? 'activo',
            ]);

            // 🔹 Verificar si se insertó correctamente
            if ($success) {
                $id = $this->pdo->lastInsertId();
                $this->logService->addLog("Usuario insertado con ID: $id", $userId);

                return $id;
            } else {
                $this->logService->addLog("Error: No se pudo insertar el usuario.", $userId);
                return false;
            }
        } catch (PDOException $e) {
            $this->logService->addLog("Error en addUser: " . $e->getMessage());
            return false;
        }
    }


    public function updateUser($data)
    {
        try {
            $query = "UPDATE usuarios SET rol_id = :rol_id";
    
            // Si hay contraseña, agregarla a la consulta
            if (!empty($data['contrasena'])) {
                $query .= ", contrasena = :contrasena";
            }
    
            $query .= " WHERE id = :user_id";
    
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':rol_id', $data['rol'], PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
    
            // Si hay contraseña, agregarla a la ejecución
            if (!empty($data['contrasena'])) {
                $stmt->bindValue(':contrasena', password_hash($data['contrasena'], PASSWORD_BCRYPT), PDO::PARAM_STR);
            }
    
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error en updateUser: " . $e->getMessage());
            return false;
        }
    }

    public function canLoginToday($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM registros 
            WHERE usuario_id = :userId 
            AND DATE(fecha) = CURDATE()
        ");
        $stmt->execute(['userId' => $userId]);
        $count = $stmt->fetchColumn();

        $this->logService->addLog(
            "Verificación de inicio de sesion para usuario ID: $userId (Puede iniciar sesion: " . ($count == 0 ? "Sí" : "No") . ")",
            $userId
        );

        return $count == 0;
    }

    public function deleteUser($id)
    {
        $user = $this->getUserById($id);
        if (!$user) {
            $this->logService->addLog("Intento de eliminar usuario no encontrado (ID: $id)", $id);
            return 0;
        }

        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);

        $this->logService->addLog("Usuario eliminado: {$user->email} (ID: $id)", $id);

        return $stmt->rowCount();
    }

    public function getRoleName($roleId)
    {
        $stmt = $this->pdo->prepare("SELECT nombre FROM roles WHERE id = :role_id");
        $stmt->execute(['role_id' => $roleId]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ? $role['nombre'] : 'Desconocido';
    }

    public function getUserSchedule($userId)
    {
        $stmt = $this->pdo->prepare("
            SELECT hora_inicio, hora_fin 
            FROM usuarios 
            WHERE id = :user_id
        ");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
