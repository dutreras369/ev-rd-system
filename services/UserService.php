<?php

require_once __DIR__ . '/../models/User.php';

class UserService {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($data) => new User($data), $users);
    }

    public function getUserById($id) {
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
    
        if ($data) {
            return new User($data); // Devuelve una instancia del modelo User
        }
    
        return null; // Devuelve null si no se encuentra el usuario
    }

    public function addUser(User $user) {
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
        return $this->pdo->lastInsertId();
    }    

    public function updateUser(User $user) {
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
    
        // Retorna true si no hay registros para hoy, es decir, el usuario puede registrar
        return $count == 0;
    }

    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->rowCount();
    }
}
