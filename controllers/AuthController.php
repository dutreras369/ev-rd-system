<?php 

require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../helpers/SessionManager.php';

class AuthController {
    private $userService;

    public function __construct($pdo) {
        $this->userService = new UserService($pdo); // Inicializar correctamente UserService
    }

    // Función para registrar logs
    private function logEvent($message) {
        $logFile = __DIR__ . '/../logs/auth.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[$timestamp] $message" . PHP_EOL;

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }

    public function login($email, $password) {
        $this->logEvent("Intento de inicio de sesión para: $email");

        if (empty($email) || empty($password)) {
            $this->logEvent("Error: Campos vacíos en el intento de inicio de sesión para: $email");
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
                    $this->logEvent("Error: El usuario $email ya registró entrada/salida hoy.");
                    return [
                        'success' => false,
                        'error' => 'Ya ha registrado su entrada/salida hoy.',
                        'error_code' => 403,
                    ];
                }

                SessionManager::loginUser($user->id, $user->rol); // Crear la sesión
                $this->logEvent("Inicio de sesión exitoso para: $email, Rol: {$user->rol}");

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
                $this->logEvent("Error: Contraseña incorrecta para: $email");
            }
        } else {
            $this->logEvent("Error: Usuario no encontrado con email: $email");
        }

        return [
            'success' => false,
            'error' => 'Credenciales inválidas.',
            'error_code' => 401,
        ];
    }
}
