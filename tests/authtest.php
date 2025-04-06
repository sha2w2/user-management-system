<?php
require_once __DIR__ . '/../autoload.php';

use PHPUnit\Framework\TestCase;
use App\Models\Admin;
use App\Models\RegularUser;
use App\Services\DatabaseService;

class AuthTest extends TestCase {
    private $dbService;

    protected function setUp(): void {
        $this->dbService = new DatabaseService();
        // Add test user
        $this->dbService->createUser('Test Admin', 'testadmin@example.com', 'test123', 'admin');
        $this->dbService->createUser('Test User', 'testuser@example.com', 'test123', 'user');
    }

    protected function tearDown(): void {
        // Clean up test users
        $this->dbService->deleteUser($this->dbService->getUserByEmail('testadmin@example.com')['id']);
        $this->dbService->deleteUser($this->dbService->getUserByEmail('testuser@example.com')['id']);
    }

    public function testAdminLogin() {
        $admin = new Admin();
        $result = $admin->login('testadmin@example.com', 'test123');
        $this->assertTrue($result);
        $this->assertEquals('Admin', $admin->userRole());
    }

    public function testRegularUserLogin() {
        $user = new RegularUser();
        $result = $user->login('testuser@example.com', 'test123');
        $this->assertTrue($result);
        $this->assertEquals('Regular User', $user->userRole());
    }

    public function testInvalidLogin() {
        $admin = new Admin();
        $result = $admin->login('nonexistent@example.com', 'wrongpassword');
        $this->assertFalse($result);
    }
}0