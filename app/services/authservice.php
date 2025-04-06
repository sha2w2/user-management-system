<?php
namespace App\Services;

use App\Core\AuthInterface;
use App\Models\Admin;
use App\Models\RegularUser;
use App\Services\SessionService;

class AuthService {
    private $sessionService;

    public function __construct() {
        $this->sessionService = new SessionService();
    }

    public function authenticate($email, $password, $role) {
        if ($role === 'admin') {
            $user = new Admin();
        } else {
            $user = new RegularUser();
        }

        if ($user->login($email, $password)) {
            $this->sessionService->set('user', [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'role' => $user->getRole()
            ]);
            return true;
        }

        return false;
    }

    public function logout() {
        $user = $this->sessionService->get('user');
        
        if ($user['role'] === 'admin') {
            $admin = new Admin();
            $admin->logout();
        } else {
            $regularUser = new RegularUser();
            $regularUser->logout();
        }
        
        $this->sessionService->destroy();
        return true;
    }

    public function isAuthenticated() {
        return $this->sessionService->isLoggedIn();
    }

    public function getUserRole() {
        $user = $this->sessionService->get('user');
        return $user['role'] ?? null;
    }
}