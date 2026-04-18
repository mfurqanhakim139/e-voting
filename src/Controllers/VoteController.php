<?php
namespace Ugkmb\Evoting\Controllers;

use Ugkmb\Evoting\Config\Database;
use PDO;
use PDOException;

class VoteController {

    /**
     * BARU: Menampilkan halaman "Pilih Event Pemilihan"
     * Ini dipanggil oleh GET /vote
     */
    public function showEventSelectionPage() {
        try {
            $pdo = Database::getConnection();
            
            // 1. Ambil SEMUA event yang 'active' (LIMIT 1 dihapus)
            $stmt = $pdo->prepare("SELECT * FROM elections WHERE status = 'active' ORDER BY name ASC");
            $stmt->execute();
            $elections = $stmt->fetchAll();

            // 2. Jika tidak ada event aktif sama sekali, tampilkan halaman 'tunggu'
            if (empty($elections)) {
                require_once __DIR__ . '/../../templates/voter/no_election.php';
                return;
            }
            
            // 3. Ambil data pemilih dari session
            $nama_pemilih = htmlspecialchars($_SESSION['voter_name'] ?? 'Pemilih');
            $nipd_pemilih = htmlspecialchars($_SESSION['voter_nipd'] ?? '');

            // 4. Tampilkan halaman "Pilih Event" yang baru
            require_once __DIR__ . '/../../templates/voter/select_event.php';

        } catch (\PDOException $e) {
            echo "Error: Terjadi masalah saat mengambil data pemilihan. " . $e->getMessage();
        }
    }

    /**
     * DIPERBARUI: Menampilkan halaman "Surat Suara" untuk 1 event
     * Ini dipanggil oleh GET /vote/{id}
     */
    public function showVotePage($electionId) {
        try {
            $pdo = Database::getConnection();
            
            // 1. Ambil data event SPESIFIK berdasarkan ID
            $stmt = $pdo->prepare("SELECT * FROM elections WHERE id = ? AND status = 'active'");
            $stmt->execute([$electionId]);
            $election = $stmt->fetch();

            // 2. Jika event tidak ada atau tidak aktif, arahkan kembali
            if (!$election) {
                // Mungkin event sudah ditutup, arahkan ke halaman pilih event
                $this->redirect('/vote?error=event_closed');
                return;
            }

            // 3. Ambil kandidat untuk event tersebut
            $stmt = $pdo->prepare("SELECT * FROM candidates WHERE election_id = :election_id ORDER BY nomor_urut ASC");
            $stmt->execute([':election_id' => $election['id']]);
            $candidates = $stmt->fetchAll();

            // 4. Ambil data pemilih dari session
            $nama_pemilih = htmlspecialchars($_SESSION['voter_name'] ?? 'Pemilih');
            $nipd_pemilih = htmlspecialchars($_SESSION['voter_nipd'] ?? '');

            // 5. Tampilkan halaman voting (file vote.php yang sudah ada)
            require_once __DIR__ . '/../../templates/voter/vote.php';

        } catch (\PDOException $e) {
            echo "Error: Terjadi masalah saat mengambil data pemilihan. " . $e->getMessage();
        }
    }

    /**
     * Memproses suara yang masuk (TIDAK BERUBAH)
     * Ini dipanggil oleh POST /vote/submit
     */
    public function submitVote() {
        $voter_id = $_SESSION['voter_id'] ?? null;
        $election_id = $_POST['election_id'] ?? null;
        $candidate_id = $_POST['candidate_id'] ?? null; // Sesuai perbaikan sebelumnya

        if (!$voter_id || !$election_id || !$candidate_id) {
            // Arahkan kembali ke halaman surat suara SPESIFIK
            $this->redirect('/vote/' . $election_id . '?error=invaliddata');
            return;
        }

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                "INSERT INTO votes (voter_id, election_id, candidate_id) 
                 VALUES (:voter_id, :election_id, :candidate_id)"
            );
            
            $stmt->execute([
                ':voter_id' => $voter_id,
                ':election_id' => $election_id,
                ':candidate_id' => $candidate_id
            ]);

            $updateStmt = $pdo->prepare("UPDATE voters SET has_voted = 1, last_voted_at = NOW() WHERE id = :voter_id");
            $updateStmt->execute([':voter_id' => $voter_id]);

            $this->redirect('/terima-kasih');

        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                // Arahkan kembali ke halaman "Pilih Event" dengan error
                $this->redirect('/vote?error=alreadyvoted');
            } else {
                $this->redirect('/vote/' . $election_id . '?error=dberror&code=' . $e->errorInfo[1]);
            }
        }
    }

    /**
     * Menampilkan halaman "Terima Kasih" (TIDAK BERUBAH)
     */
    public function showThankYouPage() {
        $nama_pemilih = $_SESSION['voter_name'] ?? 'Mahasiswa';
        
        session_unset();
        session_destroy();

        require_once __DIR__ . '/../../templates/voter/terima-kasih.php';
    }

    // Fungsi helper redirect
    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}