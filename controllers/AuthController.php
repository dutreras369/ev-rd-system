<?php 

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class AuthController {
    private $userService;

    public function __construct($pdo) {
        $this->userService = new UserService($pdo); // Inicializar correctamente UserService
    }
    
    public function login($email, $password) {
        $user = $this->userService->getUserByEmail($email);
    
        if ($user && password_verify($password, $user['contrasena'])) {
            SessionManager::loginUser($user['id'], $user['rol']);
            return [
                'success' => true,
                'message' => 'Inicio de sesión exitoso',
                'redirect_url' => $user['rol'] === 'admin' 
                    ? '/dashboard/admin.php' 
                    : '/dashboard/user.php',
            ];
        }
    
        return [
            'success' => false,
            'error' => 'Credenciales inválidas.',
        ];
    }
    
}
