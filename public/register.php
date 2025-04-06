<?php
require_once 'C:\xampp\htdocs\user-management-system\autoload.php';

use App\Services\RegistrationService;
use App\Services\AuthService;

session_start();

// Initialize services
$authService = new AuthService();
$registrationService = new RegistrationService();

// Redirect if already logged in
if ($authService->isAuthenticated()) {
    $role = $authService->getUserRole();
    header("Location: /" . ($role === 'admin' ? 'admin' : 'user') . "/dashboard.php");
    exit;
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    try {
        // Sanitize inputs
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate inputs
        if (empty($name) || empty($email) || empty($password) || empty($confirmPassword)) {
            throw new Exception("All fields are required");
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match");
        }
        
        if (strlen($password) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }

        // Attempt registration
        if ($registrationService->register($name, $email, $password)) {
            $_SESSION['registration_success'] = true;
            header("Location: /login.php?registration=success");
            exit;
        }
        
        throw new Exception("Email already exists");
        
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        
        <form method="POST" novalidate>
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" 
                       value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email"
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password (min 8 chars):</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
            </div>
            
            <button type="submit" name="register" class="btn-primary">Create Account</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="/login.php">Sign in</a>
        </div>
    </div>
</body>
</html>
