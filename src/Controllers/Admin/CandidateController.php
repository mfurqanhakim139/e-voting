<?php
namespace Ugkmb\Evoting\Controllers\Admin;

use Ugkmb\Evoting\Config\Database;
use PDO;

class CandidateController {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    // Mendapatkan data event (untuk helper)
    private function getElection($electionId) {
        $stmt = $this->pdo->prepare("SELECT * FROM elections WHERE id = ?");
        $stmt->execute([$electionId]);
        return $stmt->fetch();
    }

    // Menampilkan daftar kandidat untuk sebuah event
    public function index($electionId) {
        $election = $this->getElection($electionId);
        $stmt = $this->pdo->prepare("SELECT * FROM candidates WHERE election_id = ? ORDER BY nomor_urut ASC");
        $stmt->execute([$electionId]);
        $candidates = $stmt->fetchAll();
        
        require_once __DIR__ . '/../../../templates/admin/candidates/index.php';
    }

    // Menampilkan form tambah kandidat
    public function create($electionId) {
        $election = $this->getElection($electionId);
        $candidate = []; // Data kosong
        
        require_once __DIR__ . '/../../../templates/admin/candidates/form.php';
    }

    // Menyimpan kandidat baru (termasuk upload foto)
    public function store($electionId) {
        $nomor_urut = $_POST['nomor_urut'];
        $name = $_POST['name'];
        $visi = $_POST['visi'] ?? '';
        $misi = $_POST['misi'] ?? '';
        
        // Proses Upload Foto
        $photo_url = $this->handleUpload($_FILES['photo']);

        $stmt = $this->pdo->prepare(
            "INSERT INTO candidates (election_id, nomor_urut, name, visi, misi, photo_url) 
             VALUES (:eid, :no, :name, :visi, :misi, :photo)"
        );
        $stmt->execute([
            ':eid' => $electionId,
            ':no' => $nomor_urut,
            ':name' => $name,
            ':visi' => $visi,
            ':misi' => $misi,
            ':photo' => $photo_url
        ]);

        $this->redirect('/admin/elections/' . $electionId . '/candidates');
    }

    // Menampilkan form edit kandidat
    public function edit($electionId, $candidateId) {
        $election = $this->getElection($electionId);
        $stmt = $this->pdo->prepare("SELECT * FROM candidates WHERE id = ? AND election_id = ?");
        $stmt->execute([$candidateId, $electionId]);
        $candidate = $stmt->fetch();

        require_once __DIR__ . '/../../../templates/admin/candidates/form.php';
    }

    // Memperbarui kandidat (termasuk update foto jika ada)
    public function update($electionId, $candidateId) {
        $nomor_urut = $_POST['nomor_urut'];
        $name = $_POST['name'];
        $visi = $_POST['visi'] ?? '';
        $misi = $_POST['misi'] ?? '';
        
        // Ambil data foto lama
        $stmt_old = $this->pdo->prepare("SELECT photo_url FROM candidates WHERE id = ?");
        $stmt_old->execute([$candidateId]);
        $old_photo = $stmt_old->fetchColumn();

        // Cek apakah ada foto baru yang diupload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photo_url = $this->handleUpload($_FILES['photo']);
            // Hapus foto lama jika ada
            if ($old_photo && file_exists(__DIR__ . '/../../../public' . $old_photo)) {
                unlink(__DIR__ . '/../../../public' . $old_photo);
            }
        } else {
            // Gunakan foto lama
            $photo_url = $_POST['old_photo_url'] ?? $old_photo;
        }

        $stmt = $this->pdo->prepare(
            "UPDATE candidates SET nomor_urut = :no, name = :name, visi = :visi, misi = :misi, photo_url = :photo
             WHERE id = :id AND election_id = :eid"
        );
        $stmt->execute([
            ':no' => $nomor_urut,
            ':name' => $name,
            ':visi' => $visi,
            ':misi' => $misi,
            ':photo' => $photo_url,
            ':id' => $candidateId,
            ':eid' => $electionId
        ]);

        $this->redirect('/admin/elections/' . $electionId . '/candidates');
    }

    // Menghapus kandidat
    public function destroy($electionId, $candidateId) {
        // Ambil path foto untuk dihapus
        $stmt = $this->pdo->prepare("SELECT photo_url FROM candidates WHERE id = ?");
        $stmt->execute([$candidateId]);
        $photo_url = $stmt->fetchColumn();
        
        // 1. Hapus dari database
        $stmt_delete = $this->pdo->prepare("DELETE FROM candidates WHERE id = ? AND election_id = ?");
        $stmt_delete->execute([$candidateId, $electionId]);
        
        // 2. Hapus file fotonya
        if ($photo_url && file_exists(__DIR__ . '/../../../public' . $photo_url)) {
            unlink(__DIR__ . '/../../../public' . $photo_url);
        }

        $this->redirect('/admin/elections/' . $electionId . '/candidates');
    }

    // Fungsi helper upload
    private function handleUpload($file) {
        if (!isset($file) || $file['error'] != 0) {
            return null; // Tidak ada file atau error
        }
        
        $uploadDir = __DIR__ . '/../../../public/uploads/';
        // Pastikan folder ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '-' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return '/uploads/' . $fileName; // Kembalikan path relatif
        }
        
        return null;
    }

    // Fungsi helper redirect
    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }
}