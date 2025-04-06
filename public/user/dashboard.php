<?php
require_once __DIR__ . '/../../autoload.php';

use App\Services\AuthService;
use App\Services\SessionService;
use App\Models\RegularUser;

$authService = new AuthService();
$sessionService = new SessionService();

// Redirect if not authenticated or not regular user
if (!$authService->isAuthenticated() || $authService->getUserRole() !== 'user') {
    header("Location: /index.php");
    exit;
}

$userData = $sessionService->get('user');
$user = new RegularUser($userData['id'], $userData['name'], $userData['email'], null, $userData['role']);

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    
    if ($user->updateProfile($name, $email)) {
        $sessionService->set('user', [
            'id' => $userData['id'],
            'name' => $name,
            'email' => $email,
            'role' => $userData['role']
        ]);
        header("Location: /user/dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h1>Welcome, <?= htmlspecialchars($userData['name']) ?></h1>
            <nav>
                <a href="/user/dashboard.php">Profile</a>
                <a href="/logout.php">Logout</a>
            </nav>
        </header>
        
        <section class="profile-section">
            <h2>Your Profile</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($userData['name']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>" required>
                </div>
                
                <button type="submit" name="update_profile">Update Profile</button>
            </form>
        </section>
    </div>
</body>
</html>