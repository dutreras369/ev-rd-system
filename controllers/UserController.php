<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../services/UserService.php';
require_once __DIR__ . '/../services/LogService.php';

class UserController {
    private $userService;
    private $logService;

    public function __construct() {
        $this->userService = new UserService();
        $this->logService = new LogService();
    }

    /**
     * Obtener todos los usuarios.
     */
    public function listUsers() {
        try {
            $users = $this->userService->getAllUsers();
            return [
                'success' => true,
                'users' => $users
            ];
        } catch (Exception $e) {
            $this->logService->addLog("Error al listar usuarios: " . $e->getMessage(), null);
            return [
                'success' => false,
                'error' => 'Error al obtener la lista de usuarios.',
                'error_details' => $e->getMessage()
            ];
        }
    }

    /**
     * Obtener un usuario por ID.
     */
    public function showUser($id) {
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
    public function createUser($data) {
        try {
            $user = new User($data);
            $userId = $this->userService->addUser($user);
            $this->logService->addLog("Usuario creado con ID: $userId", $userId);
            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente.',
                'user_id' => $userId
            ];
        } catch (Exception $e) {
            $this->logService->addLog("Error al crear usuario: " . $e->getMessage(), null);
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
    public function editUser($id, $data) {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return ['success' => false, 'error' => 'Usuario no encontrado.'];
            }

            foreach ($data as $key => $value) {
                if (property_exists($user, $key)) {
                    $user->$key = $value;
                }
            }

            $updated = $this->userService->updateUser($user);
            if ($updated) {
                $this->logService->addLog("Usuario actualizado ID: $id", $id);
            }

            return [
                'success' => $updated,
                'message' => $updated ? 'Usuario actualizado correctamente.' : 'No se realizaron cambios.'
            ];
        } catch (Exception $e) {
            $this->logService->addLog("Error al actualizar usuario ID: $id - " . $e->getMessage(), null);
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
    public function removeUser($id) {
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
}
