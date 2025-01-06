<?php

require_once __DIR__ . '/../services/UserService.php';

class UserController {
    private $userService;

    public function __construct($pdo) {
        $this->userService = new UserService($pdo);
    }

    public function listUsers() {
        return $this->userService->getAllUsers();
    }

    public function showUser($id) {
        return $this->userService->getUserById($id);
    }

    public function createUser($data) {
        $user = new User($data);
        return $this->userService->addUser($user);
    }

    public function editUser($id, $data) {
        $user = $this->userService->getUserById($id);
        if ($user) {
            foreach ($data as $key => $value) {
                if (property_exists($user, $key)) {
                    $user->$key = $value;
                }
            }
            return $this->userService->updateUser($user);
        }
        return false;
    }

    public function removeUser($id) {
        return $this->userService->deleteUser($id);
    }
}
