<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;

class DashboardController {

    public function index() {
        try {
            $pdo = Database::getConnection();

            // Ambil statistik dasar
            $voterCount = $pdo->query("SELECT COUNT(id) FROM voters")->fetchColumn();
            $voteCount = $pdo->query("SELECT COUNT(id) FROM votes")->fetchColumn();
            $electionCount = $pdo->query("SELECT COUNT(id) FROM elections")->fetchColumn();
            $candidateCount = $pdo->query("SELECT COUNT(id) FROM candidates")->fetchColumn();

        } catch (\PDOException $e) {
            // Jika ada error, set ke 0
            $voterCount = 0;
            $voteCount = 0;
            $electionCount = 0;
            $candidateCount = 0;
            $error_message = $e->getMessage();
        }

        // Panggil view dan kirimkan data statistik
        require_once __DIR__ . '/../../../templates/admin/dashboard.php';
    }
}