<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;

class ResultController {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    /**
     * Menampilkan halaman untuk memilih event
     */
    public function index() {
        $stmt = $this->pdo->query(
            "SELECT id, name, status FROM elections ORDER BY id DESC"
        );
        $elections = $stmt->fetchAll();

        require_once __DIR__ . '/../../../templates/admin/results/index.php';
    }

    /**
     * Menampilkan hasil detail (Quick Count) untuk 1 event
     */
    public function show($electionId) {
        $data = $this->getResultsData($electionId);
        
        // Kirim data ke view
        require_once __DIR__ . '/../../../templates/admin/results/show.php';
    }

    /**
     * Menampilkan halaman cetak untuk 1 event
     */
    public function print($electionId) {
        $data = $this->getResultsData($electionId);
        
        // Kirim data ke view cetak
        require_once __DIR__ . '/../../../templates/admin/results/print.php';
    }

    /**
     * Fungsi Inti: Mengambil dan memproses data hasil
     */
    private function getResultsData($electionId) {
        // 1. Ambil info event
        $stmt_election = $this->pdo->prepare("SELECT * FROM elections WHERE id = ?");
        $stmt_election->execute([$electionId]);
        $election = $stmt_election->fetch();

        if (!$election) {
            die("Error: Event pemilihan tidak ditemukan.");
        }

        // 2. Ambil total suara masuk untuk event ini
        $stmt_total = $this->pdo->prepare("SELECT COUNT(id) FROM votes WHERE election_id = ?");
        $stmt_total->execute([$electionId]);
        $totalVotes = $stmt_total->fetchColumn();
        
        // 3. Ambil total DPT
        $totalVoters = $this->pdo->query("SELECT COUNT(id) FROM voters")->fetchColumn();
        
        // 4. Query utama: Ambil kandidat DAN jumlah suaranya
        
        // ==================================
        //           INI PERBAIKANNYA
        // ==================================
        $sql = "
            SELECT 
                c.id, c.name, c.nomor_urut, c.photo_url,
                COUNT(v.id) AS vote_count
            FROM 
                candidates c
            LEFT JOIN 
                -- Gunakan :vote_eid
                votes v ON c.id = v.candidate_id AND v.election_id = :vote_eid
            WHERE 
                -- Gunakan :candidate_eid
                c.election_id = :candidate_eid
            GROUP BY 
                c.id, c.name, c.nomor_urut, c.photo_url
            ORDER BY 
                c.nomor_urut ASC
        ";
        
        $stmt_results = $this->pdo->prepare($sql);
        // Kirim dua parameter
        $stmt_results->execute([
            ':vote_eid' => $electionId,
            ':candidate_eid' => $electionId
        ]);
        // ==================================
        //         AKHIR PERBAIKAN
        // ==================================
        
        $results = $stmt_results->fetchAll();

        // 5. Siapkan data (termasuk persentase)
        $chartLabels = [];
        $chartData = [];
        $processedResults = [];
        
        foreach ($results as $row) {
            $percentage = ($totalVotes > 0) ? ($row['vote_count'] / $totalVotes) * 100 : 0;
            
            $processedResults[] = [
                'name' => $row['name'],
                'nomor_urut' => $row['nomor_urut'],
                'photo_url' => $row['photo_url'],
                'vote_count' => $row['vote_count'],
                'percentage' => round($percentage, 2)
            ];
            
            // Data untuk Chart.js
            $chartLabels[] = $row['name'] . ' (' . $row['nomor_urut'] . ')';
            $chartData[] = $row['vote_count'];
        }
        
        // 6. Kembalikan semua data yang dibutuhkan oleh view
        return [
            'election' => $election,
            'totalVotes' => $totalVotes,
            'totalVoters' => $totalVoters,
            'results' => $processedResults,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode($chartData)
        ];
    }
}