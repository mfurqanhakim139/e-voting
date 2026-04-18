<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;
use PDOException;

class VoterController {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Menampilkan daftar semua pemilih (DPT)
     */
    public function index() {
        // Ambil semua pemilih, urutkan berdasarkan yang sudah vote
        $stmt = $this->pdo->query(
            "SELECT id, nipd, nm_pd, alias_prodi, has_voted, last_voted_at 
             FROM voters 
             ORDER BY has_voted DESC, nm_pd ASC"
        );
        $voters = $stmt->fetchAll();
        
        require_once __DIR__ . '/../../../templates/admin/voters/index.php';
    }

    /**
     * Menampilkan detail satu pemilih
     */
    public function show($voterId) {
        $stmt = $this->pdo->prepare("SELECT * FROM voters WHERE id = ?");
        $stmt->execute([$voterId]);
        $voter = $stmt->fetch();

        if (!$voter) {
            echo "Error: Voter tidak ditemukan.";
            return;
        }
        
        // Cari tahu dia memilih siapa (jika sudah)
        $voteStmt = $this->pdo->prepare(
            "SELECT c.name, v.voted_at 
             FROM votes v
             JOIN candidates c ON v.candidate_id = c.id
             WHERE v.voter_id = ?"
        );
        $voteStmt->execute([$voterId]);
        $vote_history = $voteStmt->fetchAll(); // Pakai fetchAll untuk jaga-jaga

        require_once __DIR__ . '/../../../templates/admin/voters/show.php';
    }

    /**
     * Aksi untuk mereset suara pemilih
     */
    public function resetVote($voterId) {
        try {
            // Kita harus melakukan ini dalam "transaksi"
            // Jika salah satu query gagal, semua akan dibatalkan
            $this->pdo->beginTransaction();
            
            // 1. Hapus catatan suara dari tabel 'votes'
            $stmt_delete = $this->pdo->prepare("DELETE FROM votes WHERE voter_id = ?");
            $stmt_delete->execute([$voterId]);
            
            // 2. Update status di tabel 'voters'
            $stmt_update = $this->pdo->prepare(
                "UPDATE voters 
                 SET has_voted = 0, last_voted_at = NULL 
                 WHERE id = ?"
            );
            $stmt_update->execute([$voterId]);
            
            // Jika semua berhasil, konfirmasi
            $this->pdo->commit();

            // Kirim pesan sukses kembali ke halaman DPT
            $_SESSION['flash_message'] = "Suara pemilih berhasil direset.";
            
        } catch (PDOException $e) {
            // Jika ada error, batalkan semua
            $this->pdo->rollBack();
            $_SESSION['flash_message'] = "Gagal mereset suara: " . $e->getMessage();
        }
        
        // Kembalikan admin ke halaman daftar DPT
        $this->redirect('/admin/voters');
    }

    // Fungsi helper
    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}