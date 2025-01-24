<?php 

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class AuthController {
    private $userService;

    public function __construct() {
        $this->userService = new UserService(); // Inicializar correctamente UserService
    }
    
    public function login($email, $password) {
        try {
            $user = $this->userService->getUserByEmail($email);
    
            if ($user && password_verify($password, $user->contrasena)) {
                SessionManager::loginUser($user->id, $user->rol);
                Logger::info("Inicio de sesión exitoso para el usuario: $email");
                return [
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso',
                    'redirect_url' => $user->rol === 'admin' 
                        ? '/dashboard/admin.php' 
                        : '/dashboard/user.php',
                ];
            }
    
            Logger::warning("Credenciales inválidas para el usuario: $email");
            return ['success' => false, 'error' => 'Credenciales inválidas.'];
        } catch (Exception $e) {
            Logger::error("Error en el login: " . $e->getMessage());
            return ['success' => false, 'error' => 'Error en el sistema.'];
        }
    }
    
    
}
