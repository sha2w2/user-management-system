<?php
namespace App\Models;

use App\Core\AbstractUser;
use App\Core\AuthInterface;
use App\Services\DatabaseService;

class RegularUser extends AbstractUser implements AuthInterface {
    public function userRole() {
        return "Regular User";
    }

    public function login($email, $password) {
        $dbService = new DatabaseService();
        $userData = $dbService->getUserByEmail($email);
        
        if ($userData && $userData['role'] === 'user' && password_verify($password, $userData['password'])) {
            $this->id = $userData['id'];
            $this->name = $userData['name'];
            $this->email = $userData['email'];
            $this->password = $userData['password'];
            $this->role = $userData['role'];
            
            return true;
        }
        
        return false;
    }

    public function logout() {
        session_destroy();
        return true;
    }

    public function updateProfile($name, $email) {
        $dbService = new DatabaseService();
        return $dbService->updateUser($this->id, $name, $email);
    }
}