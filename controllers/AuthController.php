<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/LogService.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class AuthController
{
    private $userService;
    private $logService;

    public function __construct()
    {
        $this->userService = new UserService(); // Inicializar UserService
        $this->logService = new LogService();   // Inicializar LogService
    }

    /**
     * Manejar el inicio de sesión de usuarios.
     */
    public function login($email, $password)
    {
        try {
            $user = $this->userService->getUserByEmail($email);

            if ($user && password_verify($password, $user->contrasena)) {
                SessionManager::loginUser($user->id, $user->rol_id);

                // Registrar log del inicio de sesión exitoso
                $this->logService->addLog("Inicio de sesión exitoso para el usuario: $email", $user->id);

                return [
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'user' => [
                        'id' => $user->id,
                        'nombre' => $user->nombre,
                        'email' => $user->email,
                        'rol_id' => $user->rol_id,  // Agregar el ID del rol
                        'rol' => $this->userService->getRoleName($user->rol_id), // Obtener el nombre del rolz
                        'token' => SessionManager::getToken()

                    ],
                    'redirect_url' => ($user->rol_id == 1)
                        ? BASE_URL . '/public/dashboard/admin.php'
                        : BASE_URL . '/public/dashboard/user.php',
                ];
            }

            // Registrar log de intento fallido
            $this->logService->addLog("Intento fallido de inicio de sesión para el usuario: $email", null);

            return [
                'success' => false,
                'error' => 'Credenciales inválidas.',
                'error_details' => 'Usuario o contraseña incorrectos',
            ];
        } catch (Exception $e) {
            // Registrar log de error del sistema
            $this->logService->addLog("Error en el login: " . $e->getMessage(), null);

            return [
                'success' => false,
                'error' => 'Error en el sistema. Por favor, contacte al administrador.',
                'error_details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Obtener el estado actual de la sesión del usuario.
     */
    public function sessionStatus()
    {
        return [
            'success' => true,
            'is_authenticated' => SessionManager::isAuthenticated(),
            'user_id' => SessionManager::getAuthenticatedUserId(),
            'user_role' => SessionManager::getUserRole()
        ];
    }
    /**
     * Cerrar Sesion
     */
    public function logout($userId, $token)
    {
        $response = SessionManager::logout($userId, $token);
        return $response;
    }
}
