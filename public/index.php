<?php
require_once __DIR__ . '/../autoload.php';

use App\Services\AuthService;
use App\Services\SessionService;

$sessionService = new SessionService();
$authService = new AuthService();

// Redirect if already logged in
if ($authService->isAuthenticated()) {
    $role = $authService->getUserRole();
    header("Location: /" . ($role === 'admin' ? 'admin' : 'user') . "/dashboard.php");
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if ($authService->authenticate($email, $password, $role)) {
        $redirect = $role === 'admin' ? '/admin/dashboard.php' : '/user/dashboard.php';
        header("Location: $redirect");
        exit;
    } else {
        $error = "Invalid credentials. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System - Login</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="role">Role:</label>
                <select id="role" name="role">
                    <option value="user">Regular User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            
            <button type="submit" name="login">Login</button>
        </form>
        
        <p>Don't have an account? <a href="/register.php">Register here</a></p>
    </div>
</body>
</html>