<?php
require 'vendor/autoload.php';
use Ugkmb\Evoting\Config\Database;

// --- GANTI INI ---
$username = 'admin';
$password = 'adm!n!23'; // Password yang Anda inginkan
$full_name = 'Admin Utama';
// -----------------

try {
    // Load .env
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // Dapatkan hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare(
        "INSERT INTO admin_users (username, password_hash, full_name) 
         VALUES (:user, :pass, :name)"
    );
    $stmt->execute([
        ':user' => $username,
        ':pass' => $password_hash,
        ':name' => $full_name
    ]);

    echo "=============================================\n";
    echo "Admin berhasil dibuat!\n";
    echo "Username: " . $username . "\n";
    echo "Password: " . $password . "\n";
    echo "=============================================\n";

} catch (Exception $e) {
    echo "Gagal membuat admin: " . $e->getMessage() . "\n";
}