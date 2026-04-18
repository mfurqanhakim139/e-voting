<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;

class AuthController {

    /**
     * Menampilkan halaman login admin
     */
    public function showLoginPage() {
        require_once __DIR__ . '/../../../templates/admin/login.php';
    }

    /**
     * Memproses upaya login admin
     */
    public function processLogin() {
        // (Pastikan session sudah dimulai di index.php)
        
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$username || !$password) {
            $this->redirect('/admin/login?error=empty');
            return;
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $admin = $stmt->fetch();

            // 1. Cek username
            if (!$admin) {
                $this->redirect('/admin/login?error=wrong');
                return;
            }

            // 2. Cek password
            if (!password_verify($password, $admin['password_hash'])) {
                $this->redirect('/admin/login?error=wrong');
                return;
            }

            // Sukses! Buat session untuk admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];
            
            // Arahkan ke dashboard admin
            $this->redirect('/admin/dashboard');

        } catch (\Exception $e) {
            $this->redirect('/admin/login?error=system');
        }
    }

    /**
     * Logout admin
     */
    public function logout() {
        // (Pastikan session sudah dimulai di index.php)
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        
        $this->redirect('/admin/login?status=loggedout');
    }

    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}