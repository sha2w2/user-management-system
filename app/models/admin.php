<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;
use App\Core\LoggerTrait;
use App\Services\DatabaseService;

class Admin extends AbstractUser implements AuthInterface {
    use LoggerTrait;

    public function userRole() {
        return "Admin";
    }

    public function login($email, $password) {
        $dbService = new DatabaseService();
        $userData = $dbService->getUserByEmail($email);
        
        if ($userData && $userData['role'] === 'admin' && password_verify($password, $userData['password'])) {
            $this->id = $userData['id'];
            $this->name = $userData['name'];
            $this->email = $userData['email'];
            $this->password = $userData['password'];
            $this->role = $userData['role'];
            
            $this->logActivity("Admin {$this->name} logged in.");
            return true;
        }
        
        return false;
    }

    public function logout() {
        $this->logActivity("Admin {$this->name} logged out.");
        session_destroy();
        return true;
    }

    public function getAllUsers() {
        $dbService = new DatabaseService();
        return $dbService->getAllUsers();
    }

    public function deleteUser($userId) {
        $dbService = new DatabaseService();
        return $dbService->deleteUser($userId);
    }
}