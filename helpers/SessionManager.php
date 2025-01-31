<?php

require_once __DIR__ . '/../config/Database.php';

class SessionManager
{
    // Iniciar sesión
    public static function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            if (session_status() !== PHP_SESSION_ACTIVE) {
                error_log("Error al iniciar sesión en SessionManager");
                die("Error crítico: No se pudo iniciar la sesión.");
            }
        }
    }

    // Generar un token seguro para la sesión
    private static function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    // Establecer una variable de sesión
    public static function set($key, $value)
    {
        self::startSession();
        $_SESSION[$key] = $value;
    }

    // Obtener una variable de sesión
    public static function get($key)
    {
        self::startSession();
        return $_SESSION[$key] ?? null;
    }

    // Eliminar una variable de sesión
    public static function delete($key)
    {
        self::startSession();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    // Destruir la sesión completa y eliminar el token
    public static function destroy()
    {
        self::startSession();
        self::invalidateToken(self::getAuthenticatedUserId());
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // Registrar inicio de sesión con token
    public static function loginUser($userId, $roleId)
    {
        self::startSession();

        // Obtener el nombre del rol
        $roleName = self::getRoleName($roleId);
        if (!$roleName) {
            throw new Exception("Rol inválido para el usuario.");
        }

        // Generar token único
        $token = self::generateToken();
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';

        self::set('user_id', $userId);
        self::set('user_role', $roleName);
        self::set('role_id', $roleId);
        self::set('login_time', date('Y-m-d H:i:s'));
        self::set('token', $token);

        // Guardar en la base de datos
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO sesiones (usuario_id, rol_id, ip_address, inicio, token) 
                           VALUES (:user_id, :role_id, :ip, NOW(), :token)");
        $stmt->execute([
            ':user_id' => $userId,
            ':role_id' => $roleId,
            ':ip' => $ip,
            ':token' => $token
        ]);
    }


    // Obtener nombre del rol basado en ID
    private static function getRoleName($roleId)
    {
        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT nombre FROM roles WHERE id = :role_id");
            $stmt->execute(['role_id' => $roleId]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            return $role['nombre'] ?? null;
        } catch (PDOException $e) {
            error_log("Error al obtener el nombre del rol: " . $e->getMessage());
            return null;
        }
    }

    // Validar sesión con token
    public static function validateToken($userId, $token)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("SELECT * FROM sesiones WHERE usuario_id = :user_id AND token = :token AND expiracion > NOW()");
        $stmt->execute([':user_id' => $userId, ':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Invalidar token cuando se cierra sesión
    public static function invalidateToken($userId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM sesiones WHERE usuario_id = :user_id");
        $stmt->execute([':user_id' => $userId]);
    }

    // Obtener ID del usuario autenticado
    public static function getAuthenticatedUserId()
    {
        return self::get('user_id');
    }

    // Obtener el rol del usuario autenticado
    public static function getUserRole()
    {
        return self::get('user_role');
    }

    // Obtener el token de sesión
    public static function getToken()
    {
        return self::get('token');
    }

    // Verificar si el usuario está autenticado
    public static function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    // Verificar si el usuario es administrador
    public static function isAdmin()
    {
        return self::getUserRole() === 'admin';
    }

    // Verificar si el usuario es regular
    public static function isUser()
    {
        return self::getUserRole() === 'user';
    }

    // Validar sesión y redirigir si no está autenticado
    public static function requireAuthentication($redirectUrl = '/login.php')
    {
        if (!self::isAuthenticated()) {
            header("Location: $redirectUrl");
            exit;
        }
    }

    // Validar el rol del usuario y redirigir si no tiene permisos
    public static function requireRole($requiredRole, $redirectUrl = '/unauthorized.php')
    {
        if (self::getUserRole() !== $requiredRole) {
            header("Location: $redirectUrl");
            exit;
        }
    }

    // Cerrar sesión en la BD
    private static function closeSessionInDB($userId)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("UPDATE sesiones SET fin = NOW() WHERE usuario_id = :user_id AND fin IS NULL");
        $stmt->execute([':user_id' => $userId]);
    }

    // Registrar cierre de sesión
    public static function logout()
    {
        self::startSession();
        $userId = self::getAuthenticatedUserId();
        if ($userId) {
            self::closeSessionInDB($userId);
        }
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }
}
