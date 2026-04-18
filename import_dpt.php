<?php
require 'vendor/autoload.php';

use Ugkmb\Evoting\Config\Database;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Memulai impor DPT dari datamhs.json...\n";

try {
    $pdo = Database::getConnection();

    // Buat tabel jika belum ada
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS voters (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nipd VARCHAR(50) NOT NULL UNIQUE,
            nm_pd VARCHAR(150) NOT NULL,
            tgl_lahir_hash VARCHAR(255) NOT NULL,
            alias_prodi VARCHAR(20),
            has_voted TINYINT(1) DEFAULT 0,
            last_voted_at DATETIME DEFAULT NULL
        );
    ");
    echo "Tabel 'voters' siap.\n";
    
    $json_data = file_get_contents('datamhs.json');
    $mahasiswa_list = json_decode($json_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Error decoding JSON: " . json_last_error_msg());
    }

    echo "Data JSON dibaca: " . count($mahasiswa_list) . " data.\n";

    $stmt = $pdo->prepare("
        INSERT INTO voters (nipd, nm_pd, tgl_lahir_hash, alias_prodi)
        VALUES (:nipd, :nm_pd, :tgl_lahir_hash, :alias_prodi)
        ON DUPLICATE KEY UPDATE 
            nm_pd = VALUES(nm_pd),
            tgl_lahir_hash = VALUES(tgl_lahir_hash),
            alias_prodi = VALUES(alias_prodi)
    ");

    $sukses = 0;
    $gagal = 0;

    foreach ($mahasiswa_list as $mhs) {
        try {
            $password_hash = password_hash($mhs['tgl_lahir'], PASSWORD_DEFAULT);
            $stmt->execute([
                ':nipd' => $mhs['nipd'],
                ':nm_pd' => $mhs['nm_pd'],
                ':tgl_lahir_hash' => $password_hash,
                ':alias_prodi' => $mhs['alias_prodi']
            ]);
            $sukses++;
        } catch (Exception $e) {
            $gagal++;
        }
    }

    echo "\n--- Impor Selesai ---\n";
    echo "Sukses: $sukses\n";
    echo "Gagal: $gagal\n";

} catch (Exception $e) {
    die("ERROR: " . $e->getMessage() . "\n");
}