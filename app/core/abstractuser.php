<?php
namespace App\Core;

abstract class AbstractUser {
    protected $id;
    protected $name;
    protected $email;
    protected $password;
    protected $role;

    public function __construct($id = null, $name = null, $email = null, $password = null, $role = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;
        
        if ($password) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->password);
    }

    abstract public function userRole();
}