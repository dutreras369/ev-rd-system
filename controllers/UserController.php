<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/LogService.php';
require_once __DIR__ . '/../services/SessionService.php';

class UserController
{
    private $userService;
    private $logService;
    private $sessionService;


    public function __construct()
    {
        $this->userService = new UserService();
        $this->logService = new LogService();
        $this->sessionService = new SessionService();
    }

    /**
     * Obtener todos los usuarios.
     */
    public function listUsers()
    {
        $users = $this->userService->getUsers();

        return [
            'success' => true,
            'users' => array_map(function ($user) {
                return [
                    'id' => $user['id'],
                    'nombre' => $user['nombre'],
                    'email' => $user['email'],
                    'rol' => $user['rol']
                ];
            }, $users)
        ];
    }

    /**
     * Obtener un usuario por ID.
     */
    public function showUser($id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return ['success' => false, 'error' => 'Usuario no encontrado.'];
            }
            return ['success' => true, 'user' => $user];
        } catch (Exception $e) {
            $this->logService->addLog("Error al obtener usuario ID: $id - " . $e->getMessage(), null);
            return [
                'success' => false,
                'error' => 'Error al obtener el usuario.',
                'error_details' => $e->getMessage()
            ];
        }
    }

    /**
     * Crear un nuevo usuario.
     */
    public function createUser($data)
    {
        try {
            if (!isset($data['username'])) {
                $data['username'] = strtolower(str_replace(' ', '', $data['nombre']));
            }
    
            $user = new User($data);
            $userId = $this->userService->addUser($user, $data['usuario_id']);
    
            if (!$userId) {
                throw new Exception("No se pudo insertar el usuario en la base de datos.");
            }
    
            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente.',
                'user_id' => $userId
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'No se pudo crear el usuario.',
                'error_details' => $e->getMessage()
            ];
        }
    }
    

    /**
     * Editar un usuario existente.
     */
    public function editUser($data)
    {
        try {
            $updated = $this->userService->updateUser($data);
    
            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente.'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'No se realizaron cambios o hubo un error.'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'No se pudo actualizar el usuario.',
                'error_details' => $e->getMessage()
            ];
        }
    }

    /**
     * Eliminar un usuario por ID.
     */
    public function removeUser($id)
    {
        try {
            $deleted = $this->userService->deleteUser($id);
            if ($deleted) {
                $this->logService->addLog("Usuario eliminado ID: $id", $id);
            }
            return [
                'success' => $deleted,
                'message' => $deleted ? 'Usuario eliminado correctamente.' : 'Usuario no encontrado.'
            ];
        } catch (Exception $e) {
            $this->logService->addLog("Error al eliminar usuario ID: $id - " . $e->getMessage(), null);
            return [
                'success' => false,
                'error' => 'No se pudo eliminar el usuario.',
                'error_details' => $e->getMessage()
            ];
        }
    }

    public function getUser($userId, $token)
    {
        // 🔹 Validar si el token es válido
        if (!$this->sessionService->validateToken($userId, $token)) {
            return ['success' => false, 'error' => 'Token inválido o sesión expirada'];
        }

        // 🔹 Obtener el objeto usuario
        $userData = $this->userService->getUserById($userId);

        // 🔹 Validar que el usuario existe
        if (!$userData) {
            return ['success' => false, 'error' => 'Usuario no encontrado'];
        }

        // 🔹 Obtener hora de inicio de sesión
        $horaInicio = $this->sessionService->getSessionStartTime($userId, $token);
        $horaInicioFormatted = $horaInicio ? date('d-m-Y H:i:s', strtotime($horaInicio)) : null;

        // 🔹 Retornar los datos del usuario correctamente
        return [
            'success' => true,
            'user' => [
                'id' => $userData->id,
                'nombre' => $userData->nombre,
                'email' => $userData->email,
                'rol_id' => $userData->rol_id,
                'rol' => $this->sessionService->getRoleName($userData->rol_id),
                'hora_inicio' => $horaInicioFormatted // ✅ Ahora la fecha está en el formato correcto
            ]
        ];
    }
}
