<?php
require_once __DIR__ . '/../autoload.php';

use App\Services\AuthService;

$authService = new AuthService();
$authService->logout();

header("Location: /index.php");
exit;
?>