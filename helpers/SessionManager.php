<?php

require_once __DIR__ . '/../config/Database.php';

class SessionManager {
    // Iniciar sesión
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Establecer una variable de sesión
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // Obtener una variable de sesión
    public static function get($key) {
        return $_SESSION[$key] ?? null;
    }

    // Eliminar una variable de sesión
    public static function delete($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Destruir la sesión completa
    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
        setcookie(session_name(), '', time() - 3600, '/'); // Eliminar cookie de sesión
    }

    // Registrar inicio de sesión
    public static function loginUser($userId, $roleId) {
        self::startSession();

        // Validar el rol basado en la relación en la base de datos
        $roleName = self::getRoleName($roleId);
        if (!$roleName) {
            throw new Exception("Rol inválido para el usuario.");
        }

        self::set('user_id', $userId);
        self::set('user_role', $roleName); // Almacenar el nombre del rol
        self::set('login_time', date('Y-m-d H:i:s'));
    }

    // Obtener el nombre del rol basado en el ID del rol
    private static function getRoleName($roleId) {
        try {
            $pdo = Database::getConnection(); // Usar la clase Database para la conexión
            $stmt = $pdo->prepare("SELECT nombre FROM roles WHERE id = :role_id");
            $stmt->execute(['role_id' => $roleId]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            return $role['nombre'] ?? null;
        } catch (PDOException $e) {
            error_log("Error al obtener el nombre del rol: " . $e->getMessage());
            return null;
        }
    }

    // Validar si el usuario está autenticado
    public static function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

    // Obtener el ID del usuario autenticado
    public static function getAuthenticatedUserId() {
        return self::get('user_id');
    }

    // Obtener el rol del usuario autenticado
    public static function getUserRole() {
        return self::get('user_role');
    }

    // Registrar cierre de sesión
    public static function logout() {
        self::destroy();
    }

    // Verificar si el usuario es administrador
    public static function isAdmin() {
        return self::getUserRole() === 'admin';
    }

    // Verificar si el usuario es regular (rol 'user')
    public static function isUser() {
        return self::getUserRole() === 'user';
    }

    // Validar la sesión y redirigir si no está autenticado
    public static function requireAuthentication($redirectUrl = '/login.php') {
        if (!self::isAuthenticated()) {
            header("Location: $redirectUrl");
            exit;
        }
    }

    // Validar el rol del usuario y redirigir según corresponda
    public static function requireRole($requiredRole, $redirectUrl = '/unauthorized.php') {
        if (self::getUserRole() !== $requiredRole) {
            header("Location: $redirectUrl");
            exit;
        }
    }
}
