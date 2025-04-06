<?php
namespace App\Core;

/**
 * Authentication Interface
 * Defines required methods for user authentication
 */
interface AuthInterface {
    /**
     * Authenticate a user
     * @param string $email
     * @param string $password
     * @return bool True if authentication succeeds
     */
    public function login($email, $password);

    /**
     * Terminate user session
     * @return bool True if logout succeeds
     */
    public function logout();
}