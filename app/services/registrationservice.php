<?php
namespace App\Services;

class RegistrationService {
    private $dbService;

    public function __construct() {
        $this->dbService = new DatabaseService();
    }

    public function register($name, $email, $password, $role = 'user') {
        // Check if email already exists
        if ($this->dbService->getUserByEmail($email)) {
            return false;
        }

        // Create new user
        return $this->dbService->createUser($name, $email, $password, $role);
    }
}