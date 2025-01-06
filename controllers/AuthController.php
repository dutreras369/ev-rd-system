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

        if ($user && password_verify($password, $user->contrasena)) {
            if ($user->rol === 'user' && !$this->userService->canLoginToday($user->id)) {
                return ['error' => 'Ya ha registrado su entrada/salida hoy.'];
            }

            SessionManager::loginUser($user->id, $user->rol); // Crear la sesión
            return ['success' => true, 'role' => $user->rol];
        }

        return ['error' => 'Credenciales inválidas.'];
    }
}
