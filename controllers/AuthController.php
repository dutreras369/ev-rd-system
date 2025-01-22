<?php 

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class AuthController {
    private $userService;

    public function __construct($pdo) {
        $this->userService = new UserService($pdo); // Inicializar correctamente UserService
    }
    public function login($email, $password) {
        $this->userService->logEvent("Intento de inicio de sesión para: $email");
    
        if (empty($email) || empty($password)) {
            $this->userService->logEvent("Error: Campos vacíos en el intento de inicio de sesión", null);
            return [
                'success' => false,
                'error' => 'Email o contraseña vacíos.',
                'error_code' => 400,
            ];
        }
    
        $user = $this->userService->getUserByEmail($email);
    
        if ($user) {
            if (password_verify($password, $user->contrasena)) {
                if ($user->rol === 'user' && !$this->userService->canLoginToday($user->id)) {
                    $this->userService->logEvent("Error: El usuario $email ya registró entrada/salida hoy.", $user->id);
                    return [
                        'success' => false,
                        'error' => 'Ya ha registrado su entrada/salida hoy.',
                        'error_code' => 403,
                    ];
                }
    
                SessionManager::loginUser($user->id, $user->rol); // Crear la sesión
                $this->userService->logEvent("Inicio de sesión exitoso", $user->id);
    
                return [
                    'success' => true,
                    'message' => 'Inicio de sesión exitoso.',
                    'user' => [
                        'id' => $user->id,
                        'nombre' => $user->nombre,
                        'email' => $user->email,
                        'rol' => $user->rol,
                    ],
                    'redirect_url' => $user->rol === 'admin' 
                        ? '/public/dashboard/admin.php'
                        : '/public/dashboard/user.php',
                ];
            } else {
                $this->userService->logEvent("Error: Contraseña incorrecta para: $email", $user->id);
            }
        } else {
            $this->userService->logEvent("Error: Usuario no encontrado con email: $email");
        }
    
        return [
            'success' => false,
            'error' => 'Credenciales inválidas.',
            'error_code' => 401,
        ];
    }    
}
